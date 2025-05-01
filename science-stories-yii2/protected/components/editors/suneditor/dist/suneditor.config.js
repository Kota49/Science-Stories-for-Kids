
var suneditor_config = {
	display: 'block',
	width: '100%',
	height: '150px',
	popupDisplay: 'full',
	charCounter: true,
	charCounterLabel: 'Characters :',
	imageGalleryUrl: 'https://etyswjpn79.execute-api.ap-northeast-1.amazonaws.com/suneditor-demo',
	buttonList: [
		// default
		['fullScreen', 'removeFormat'],
		['font', 'fontSize', 'formatBlock'],
		['paragraphStyle', 'blockquote'],
		['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
		['fontColor', 'hiliteColor', 'textStyle'],
		['outdent', 'indent'],
		['align', 'horizontalRule', 'list', 'lineHeight'],
		['table', 'link', 'image', 'video', 'audio', 'math'],
		['imageGallery'],
		['showBlocks', 'codeView'],
		['preview', 'print'],
		['save', 'template'],
		// (min-width: 1546)
		['%1546', [
			['fullScreen', 'removeFormat'],
			['font', 'fontSize', 'formatBlock'],
			['paragraphStyle', 'blockquote'],
			['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
			['fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			['table', 'link', 'image', 'video', 'audio', 'math'],
			['imageGallery'],
			['showBlocks', 'codeView'],
			['-right', ':i-More Misc-default.more_vertical', 'preview', 'print', 'save', 'template']
		]],
		// (min-width: 1455)
		['%1455', [
			['fullScreen', 'removeFormat'],
			['font', 'fontSize', 'formatBlock'],
			['paragraphStyle', 'blockquote'],
			['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
			['fontColor', 'hiliteColor', 'textStyle'],

			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			['table', 'link', 'image', 'video', 'audio', 'math'],
			['imageGallery'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
		]],
		// (min-width: 1326)
		['%1326', [
			['fullScreen', 'removeFormat'],
			['font', 'fontSize', 'formatBlock'],
			['paragraphStyle', 'blockquote'],
			['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
			['fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
			['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
		]],
		// (min-width: 1123)
		['%1123', [
			['fullScreen', 'removeFormat'],
			[':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
			['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
			['fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
			['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
		]],
		// (min-width: 817)
		['%817', [
			['fullScreen', 'removeFormat'],
			[':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
			['bold', 'underline', 'italic', 'strike'],
			[':t-More Text-default.more_text', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template'],
			['-right', ':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery']
		]],
		// (min-width: 673)
		['%673', [
			['fullScreen', 'removeFormat'],
			[':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
			[':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			['align', 'horizontalRule', 'list', 'lineHeight'],
			[':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
		]],
		// (min-width: 525)
		['%525', [
			['fullScreen', 'removeFormat'],
			[':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
			[':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
			['outdent', 'indent'],
			[':e-More Line-default.more_horizontal', 'align', 'horizontalRule', 'list', 'lineHeight'],
			[':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
		]],
		// (min-width: 420)
		['%420', [
			['fullScreen', 'removeFormat'],
			[':p-More Paragraph-default.more_paragraph', 'font', 'fontSize', 'formatBlock', 'paragraphStyle', 'blockquote'],
			[':t-More Text-default.more_text', 'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'fontColor', 'hiliteColor', 'textStyle'],
			[':e-More Line-default.more_horizontal', 'outdent', 'indent', 'align', 'horizontalRule', 'list', 'lineHeight'],
			[':r-More Rich-default.more_plus', 'table', 'link', 'image', 'video', 'audio', 'math', 'imageGallery'],
			['-right', ':i-More Misc-default.more_vertical', 'showBlocks', 'codeView', 'preview', 'print', 'save', 'template']
		]]
	],
	// placeholder: 'Start typing something...',
	templates: [
		{
			name: 'Template-1',
			html: '<p>HTML source1</p>'
		},
		{
			name: 'Template-2',
			html: '<p>HTML source2</p>'
		}
	],
	codeMirror: CodeMirror,
	katex: katex,

}

function editorConfig(editor, uploadOptions) {
	$(window).click(function() {
		editor.save();
	});
	editor.onImageUploadBefore = function(files, info, core, uploadHandler) {
		try {

			const uploadFile = files[0];
			const formData = new FormData();
			formData.append("file", uploadFile);
			formData.append("model_type", uploadOptions.model_type);
			formData.append("model_id", uploadOptions.model_id);
			formData.append("_csrf", uploadOptions.csrf);
			var upload_url = uploadOptions.base_url + "file/upload-image";

			const fetchOptions = {
				method: 'post',
				body: formData
			};

			res = fetch(upload_url, fetchOptions)
				.then(response => response.json())
				.then(data => {
					if (data.success) {

						// Need to implement the image URL logic here
						response = {
							result: [
								{
									url: data.url,
									name: data.name,
									size: data.size,
								},
							],
						};
						uploadHandler(response);
					} else {
						uploadHandler(data.errorMessage)
					}
				});


		} catch (err) {
			uploadHandler(err.toString())
		}
	};
}


