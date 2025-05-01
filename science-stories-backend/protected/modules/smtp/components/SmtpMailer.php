<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\smtp\components;

use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;
use app\components\helpers\TLogHelper;
use app\modules\smtp\models\Account;
use yii\base\InvalidConfigException;
use yii\mail\BaseMailer;
use yii\symfonymailer\Message;
use yii\symfonymailer\MessageEncrypterInterface;
use yii\symfonymailer\MessageSignerInterface;
use Symfony\Component\Mailer\Exception\InvalidArgumentException;
use yii\symfonymailer\MessageWrapperInterface;

class SmtpMailer extends BaseMailer
{

    /**
     *
     * @var string message default class name.
     */
    public $messageClass = Message::class;

    private ?SymfonyMailer $symfonyMailer = null;

    /**
     *
     * @see https://symfony.com/doc/current/mailer.html#encrypting-messages
     */
    public ?MessageEncrypterInterface $encrypter = null;

    /**
     *
     * @see https://symfony.com/doc/current/mailer.html#signing-messages
     */
    public ?MessageSignerInterface $signer = null;

    public array $signerOptions = [];

    /**
     *
     * @var null|TransportInterface Symfony transport instance or its array configuration.
     */
    private ?TransportInterface $_transport = null;

    public ?Transport $transportFactory = null;

    use TLogHelper;

    public $account_id = 0;

    public $config = null;

    public $messageId;

    public $isSuccessful = false;

    public function init()
    {
        parent::init();

        if ($this->config == null && $this->account_id) {
            $this->config = Account::findOne($this->account_id);
        }

        if ($this->config == null) {
            $this->config = Account::findActive()->one();
        }
        if ($this->config == null) {
            throw new \InvalidArgumentException('smtp account doesnt exists');
        }

        $this->account_id = $this->config->id;

        $transport = [
            'scheme' => 'smtp',
            'host' => $this->config->server,
            'username' => $this->config->email,
            'password' => $this->config->getDecryptedPassword(),
            'port' => $this->config->port,
            'encryption' => $this->config->encryption,
            'options' => [
                'ssl' => true,
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false
            ],
            'enableMailerLogging' => YII_ENV == 'dev'
        ];
        $this->setTransport($transport);
    }

    public function afterSend($message, $isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;
        parent::afterSend($message, $isSuccessful);
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function sendMessage($message): bool
    {
        if (! ($message instanceof MessageWrapperInterface)) {
            throw new InvalidArgumentException(sprintf('The message must be an instance of "%s". The "%s" instance is received.', MessageWrapperInterface::class, get_class($message)));
        }
        $message = $message->getSymfonyEmail();

        if ($this->encrypter !== null) {
            $message = $this->encrypter->encrypt($message);
        }

        if ($this->signer !== null) {
            $message = $this->signer->sign($message, $this->signerOptions);
        }
        try {
            $sentMessage = $this->getTransport()->send($message);
            $this->messageId = $sentMessage->getMessageId();
            self::log(' Sent:' . $this->messageId);
        } catch (\Exception $exception) {

            self::log($exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Creates Symfony mailer instance.
     *
     * @return SymfonyMailer mailer instance.
     */
    private function createSymfonyMailer(): SymfonyMailer
    {
        return new SymfonyMailer($this->getTransport());
    }

    /**
     *
     * @return SymfonyMailer Swift mailer instance
     */
    private function getSymfonyMailer(): SymfonyMailer
    {
        if (! isset($this->symfonyMailer)) {
            $this->symfonyMailer = $this->createSymfonyMailer();
        }
        return $this->symfonyMailer;
    }

    /**
     *
     * @param PsalmTransportConfig|TransportInterface $transport
     * @throws InvalidConfigException on invalid argument.
     */
    public function setTransport($transport): void
    {
        if (! is_array($transport) && ! $transport instanceof TransportInterface) {
            throw new InvalidArgumentException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }

        $this->_transport = $transport instanceof TransportInterface ? $transport : $this->createTransport($transport);

        $this->symfonyMailer = null;
    }

    public function getTransport(): TransportInterface
    {
        /**
         *
         * @psalm-suppress RedundantPropertyInitializationCheck Yii2 configuration flow does not guarantee full initialisation
         */
        if (! isset($this->_transport)) {
            throw new InvalidConfigException('No transport was configured.');
        }
        return $this->_transport;
    }

    private function getTransportFactory(): Transport
    {
        if (isset($this->transportFactory)) {
            return $this->transportFactory;
        }
        $defaultFactories = Transport::getDefaultFactories();
        /**
         *
         * @psalm-suppress InvalidArgument Symfony's type annotation is wrong
         */
        return new Transport($defaultFactories);
    }

    /**
     *
     * @param PsalmTransportConfig $config
     * @throws InvalidConfigException
     */
    private function createTransport(array $config = []): TransportInterface
    {
        $transportFactory = $this->getTransportFactory();
        if (array_key_exists('dsn', $config)) {
            $transport = $transportFactory->fromString($config['dsn']);
        } elseif (array_key_exists('scheme', $config) && array_key_exists('host', $config)) {
            $dsn = new Dsn($config['scheme'], $config['host'], $config['username'] ?? '', $config['password'] ?? '', $config['port'] ?? null, $config['options'] ?? []);
            $transport = $transportFactory->fromDsnObject($dsn);
        } else {
            throw new InvalidConfigException('Transport configuration array must contain either "dsn", or "scheme" and "host" keys.');
        }
        return $transport;
    }
}