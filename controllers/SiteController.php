<?php

namespace app\controllers;

use Yii;
use app\models\Url;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use yii\web\Response;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionShorten()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $url = Yii::$app->request->post('url');

            // Vérification de l’URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                Yii::error("URL invalide soumise : $url", __METHOD__);
                return ['success' => false, 'message' => 'URL invalide'];
            }

            // Vérification de l'accessibilité
            $headers = @get_headers($url);
            if ($headers === false || strpos($headers[0], '200') === false) {
                Yii::error("URL inaccessible : $url | Headers: " . json_encode($headers), __METHOD__);
                return ['success' => false, 'message' => 'Le site est inaccessible'];
            }

            // Génération code unique
            do {
                $code = Yii::$app->security->generateRandomString(6);
            } while (Url::find()->where(['short_code' => $code])->exists());

            // Sauvegarde URL
            $model = new Url();
            $model->original_url = $url;
            $model->short_code = $code;

            if (!$model->save()) {
                Yii::error("Échec d'enregistrement de l'URL : " . json_encode($model->errors), __METHOD__);
                return ['success' => false, 'message' => 'Erreur lors de l’enregistrement'];
            }

            $shortUrl = Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['site/redirect', 'code' => $code]);

            // QR Code
            $qrDir = Yii::getAlias('@webroot/qr');
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0777, true);
            }
            $qrPath = "$qrDir/{$code}.png";

            try {
                $builder = new Builder(
                    writer: new PngWriter(),
                    writerOptions: [],
                    validateResult: false,
                    data: $shortUrl,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    logoPath: Yii::getAlias('@webroot/assets/bender.png'),
                    logoResizeToWidth: 50,
                    logoPunchoutBackground: true,
                    labelText: 'Scan me',
                    labelFont: new OpenSans(20),
                    labelAlignment: LabelAlignment::Center
                );
                $result = $builder->build();
                $result->saveToFile($qrPath);
            } catch (\Throwable $e) {
                Yii::error("Erreur QRCode : " . $e->getMessage(), __METHOD__);
                return ['success' => false, 'message' => 'Erreur lors de la génération du QR code'];
            }

            $qrUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/qr/{$code}.png";

            return [
                'success' => true,
                'short_url' => $shortUrl,
                'qr_url' => $qrUrl,
            ];
        } catch (\Throwable $e) {
            Yii::error("Exception non capturée : " . $e->getMessage(), __METHOD__);
            return ['success' => false, 'message' => 'Une erreur inattendue est survenue.'];
        }
    }



    public function actionRedirect($code)
    {
        $model = \app\models\Url::findOne(['short_code' => $code]);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Lien introuvable.');
        }

        // Incrémenter les clics
        $model->updateCounters(['clicks' => 1]);

        // Enregistrer l’IP dans une table log
        $log = new \app\models\UrlLog();
        $log->url_id = $model->id;
        $log->ip_address = Yii::$app->request->userIP;
        $log->visited_at = date('Y-m-d H:i:s');
        $log->save(false);

        // Rediriger vers l’URL longue
        return $this->redirect($model->original_url);
    }
}
