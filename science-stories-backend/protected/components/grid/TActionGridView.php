<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\grid;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\base\UserException;
use yii\data\ArrayDataProvider;
use yii\grid\Column;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @see use yii\grid\GridView;
 *
 */
class TActionGridView extends GridView
{

    /**
     * 
     * @var boolean
     * @desc enable or not the row click
     */
    public $enableRowClick = true;

    /**
     *
     * @var boolean
     * @desc enable or not for responsive 
     */
    public $responsive = true;

    /**
     *
     * @var bool whether the gridview can be exported.
     */
    public $exportable = false;

    const DEFAULT_POINTER = 'A';

    const WRITER_XLS = 'Xls';

    const WRITER_XLSX = 'Xlsx';

    const WRITER_ODS = 'Ods';

    const WRITER_CSV = 'Csv';

    const WRITER_HTML = 'Html';

    const WRITER_MPDF = 'Mpdf';

    /**
     *
     * @var string the layout that determines how different sections of the grid view should be organized.
     *      The following tokens will be replaced with the corresponding section contents:
     *     
     *      - `{summary}`: the summary section. See [[renderSummary()]].
     *      - `{errors}`: the filter model error summary. See [[renderErrors()]].
     *      - `{items}`: the list items. See [[renderItems()]].
     *      - `{sorter}`: the sorter. See [[renderSorter()]].
     *      - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "{summary}\n{items}\n{pager}";

    /**
     *
     * @var string writer type (format type). If not set, it will be determined automatically.
     *      Supported values:
     *     
     *      - 'Xls'
     *      - 'Xlsx'
     *      - 'Ods'
     *      - 'Csv'
     *      - 'Html'
     *      - 'Tcpdf'
     *      - 'Dompdf'
     *      - 'Mpdf'
     *     
     * @see IOFactory
     */
    public $writerType;

    /**
     *
     * @var string filename of the generated spreadsheet
     */
    public $fileName = null;

    /**
     *
     * @var array Additional options for sending the file
     */
    public $exportFileOptions = [];

    /**
     *
     * @var array options to use when rendering export link.
     */
    public $exportLinkOptions = [
        'class' => 'btn btn-success',
        'target' => '_blank'
    ];

    /**
     *
     * @var array columns to be exported. It empty gridview columns will be used.
     */
    public $exportColumns = [];

    /**
     *
     * @var string spreadsheet column index
     */
    private $columnIndex = self::DEFAULT_POINTER;

    /**
     *
     * @var int spreadsheet row index
     */
    private $rowIndex = 1;

    /**
     *
     * @var array multidimensional containing rows and columns.
     *      First level: Rows
     *      Second level: Cols
     */
    private $data = [];

    /**
     *
     * @var Spreadsheet generated
     */
    private $_document;

    /**
     * Initialize the gridview;
     *
     * @throws \yii\base\InvalidConfigException
     */
    /**
     *
     * @return bool whether a download is allowed and requested.
     */
    public $gridToolbar = [];

    public $checkbox = [];

    public function renderButtons($key, $toolbarButtons)
    {
        $label = ArrayHelper::remove($toolbarButtons, 'label', 'Button');
        $url = ArrayHelper::remove($toolbarButtons, 'url', 'url');

        $toolbarButtons = array_merge([
            'id' => "mass_button-$key",
            'class' => 'btn btn-warning ms-3',
            'href' => Url::toRoute($url),
            'title' => $key
        ], $toolbarButtons);

        $this->view->registerJs($this->js($toolbarButtons));
        return Html::a($label, true, $toolbarButtons);
    }

    protected function js($toolbarButtons)
    {
        $controller = \Yii::$app->controller->id;
        $js = <<<JS
               $('#{$toolbarButtons['id']}').click(function(e) {
            	e.preventDefault();
            	 var keys = $('#{$this->id}').yiiGridView('getSelectedRows');
            	 if ( keys != '' ) {
            		var ok = confirm("Do you really want to perform this action?");
            		if( ok ) {
            			$.ajax({
            				url  : '{$toolbarButtons['href']}',
            				type : "POST",
            				data : {
            					ids : keys,
            				},
            				success : function( response ) {
            					if ( response.status == "OK" ) {
            						 $.pjax.reload({container: '#{$controller}-pjax-grid'});
            					}
            				}
            		     });
            		}
            	 } else {
            		alert('Please select items to perform action');
            	 }
            });
        JS;
        return $js;
    }

    protected function downloadRequested()
    {
        $request = Yii::$app->getRequest();

        $grid = $request->get('export');

        return $this->exportable && $grid;
    }

    public function init()
    {
        if ($this->fileName === null) {
            $this->fileName = \Yii::$app->controller->id . "_" . \Yii::$app->controller->action->id . '.xls';
        }
        if (! isset($this->id)) {
            $this->options['id'] = $this->getId();
        }

        if ($this->downloadRequested() && ! empty($this->exportColumns)) {
            $this->emptyCell = "";
            $this->columns = $this->exportColumns;
        }

        if ($this->downloadRequested())
            $this->dataProvider->pagination = false;
        if (! empty($this->gridToolbar)) {

            $this->checkbox = array_merge([
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => true
            ], $this->checkbox);

            array_unshift($this->columns, $this->checkbox);
            echo "<div class='gridToolbar alert-warning row py-2 mb-2'>";

            if ($this->exportable) {
                $this->gridToolbar[] = [
                    'label' => '<span class="glyphicon glyphicon-export"></span>Export',
                    'title' => \Yii::t('app', 'Export'),
                    'url' => Url::current([
                        'export' => 1
                    ])
                ];
            }

            foreach ($this->gridToolbar as $key => $toolbarButtons) {
                echo $this->renderButtons($key, $toolbarButtons);
            }
            echo "</div>";
        }
        parent::init();

        if ($this->dataProvider instanceof ArrayDataProvider) {
            $this->enableRowClick = false;
        }

        if (isset($this->rowOptions)) {
            $this->tableOptions['class'] = str_replace('table-striped', '', $this->tableOptions['class']);
        }

        if ($this->enableRowClick == true) {
            $this->view->registerJs($this->jsRow());
        }
        // onclick event should alwaysif ($this->responsive) open detail view:
        if ($this->rowOptions == NULL && $this->enableRowClick == true)
            $this->rowOptions = function ($model, $key, $index, $grid) {
                // get the model name is necessary, if the grid is not the main grid
                // without this the routed view is the view of the main controller

                return [
                    'data-id' => $model->id,
                    'style' => $this->enableRowClick ? "cursor:pointer;" : '',
                    'data-name' => \yii\helpers\Inflector::camel2id(\yii\helpers\StringHelper::basename(get_class($model))),
                    'data-url' => $model->getUrl()
                ];
            };
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        if ($this->downloadRequested()) {
            if ($this->dataProvider->getCount() <= 0 || empty($this->columns))
                throw new UserException('Nothing to export');

            $response = Yii::$app->getResponse();
            $response->clearOutputBuffers();
            $response->setStatusCode(200);
            $this->prepareExportArray();
            $document = $this->getDocument();
            $document->getActiveSheet()->fromArray($this->data);
            $this->prepareSend($this->exportFileOptions);
            Yii::$app->response->send();
            Yii::$app->end();
        }

        if ($this->responsive) {
            echo "<div class='table-responsive'>";
        }
        parent::run();
        if ($this->responsive) {
            echo "</div>";
        }
    }

    protected function prepareExportArray()
    {
        $this->renderExportHeaders();
        $this->renderExportBody();
        $this->renderExportFooter();
        $this->cleanExportData();
    }

    public function renderExportHeaders()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[$this->columnIndex ++] = $column->renderHeaderCell();
        }
        $this->data[$this->rowIndex ++] = $cells;
        $this->columnIndex = self::DEFAULT_POINTER;
    }

    public function renderExportBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            $rows[] = $this->renderExportRow($model, $key, $index);
        }
    }

    public function renderExportRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells[$this->columnIndex ++] = $column->renderDataCell($model, $key, $index);
        }
        $this->columnIndex = self::DEFAULT_POINTER;

        $this->data[$this->rowIndex ++] = $cells;
    }

    public function renderExportFooter()
    {
        $cells = [];

        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[$this->columnIndex ++] = $column->renderFooterCell();
        }
        $this->data[$this->rowIndex ++] = $cells;
        $this->columnIndex = self::DEFAULT_POINTER;
    }

    /**
     * Removes all tags and encodes each cell to export.
     */
    private function cleanExportData()
    {
        foreach ($this->data as $rowKey => $row) {
            foreach ($row as $colKey => $column) {
                $cleanValue = htmlspecialchars_decode(strip_tags($column), ENT_QUOTES);
                $this->data[$rowKey][$colKey] = $cleanValue;
            }
        }
    }

    /**
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet spreadsheet document representation instance.
     */
    public function getDocument()
    {
        if (! is_object($this->_document)) {
            $this->_document = new Spreadsheet();
        }

        return $this->_document;
    }

    /**
     * Sends the rendered content as a file to the browser.
     *
     * Note that this method only prepares the response for file sending. The file is not sent
     * until [[\yii\web\Response::send()]] is called explicitly or implicitly.
     * The latter is done after you return from a controller action.
     *
     * @param array $options
     *            additional options for sending the file. The following options are supported:
     *            
     *            - `mimeType`: the MIME type of the content. Defaults to 'application/octet-stream'.
     *            - `inline`: bool, whether the browser should open the file within the browser window. Defaults to false,
     *            meaning a download dialog will pop up.
     *            
     * @return \yii\web\Response the response object.
     */
    public function prepareSend($options = [])
    {
        $writerType = $this->writerType;
        if ($writerType === null) {
            $fileExtension = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
            $writerType = ucfirst($fileExtension);
        }

        $tmpResource = tmpfile();
        if ($tmpResource === false)
            throw new \Exception('Temporary file could not be created');

        $tmpResourceMetaData = stream_get_meta_data($tmpResource);
        $tmpFileName = $tmpResourceMetaData['uri'];

        $writer = IOFactory::createWriter($this->getDocument(), $writerType);
        $writer->save($tmpFileName);
        unset($writer);

        return Yii::$app->getResponse()->sendStreamAsFile($tmpResource, $this->fileName, $options);
    }

    protected function jsRow()
    {
        return "$(document).on('click' , '.grid-view td' , function(e){
                        var id = $(this).closest('tr').data('id');
        				var url = $(this).closest('tr').data('url');
        	       		var name = $(this).closest('tr').data('name');
        	       		var target = $(e.target);
        		        if(e.target == this || target.is('p')){
        					if(!$(this).closest('tr').hasClass('filters')){
                                if ( url != null)
                                {
        		            	     location.href = url;
                                }
                            }
        			    }
                    })";
    }
}
