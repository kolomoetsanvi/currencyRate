<?php

namespace frontend\controllers;

use common\models\Currency;
use common\models\Office;
use common\models\Rate;
use DateTime;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\rest\Controller;
use yii\web\Response;

class RateController extends Controller
{
    /**
     * @return Response
     * @throws \Exception
     */
    public function actionSet()
    {
        $request = Yii::$app->getRequest();
        $currency = $request->bodyParams['currency'];
        $buy = $request->bodyParams['buy'];
        $sell = $request->bodyParams['sell'];
        $beginsAt = $request->bodyParams['begins_at'];
        $officeId = $request->bodyParams['office_id'];
        if (!isset($currency, $buy, $sell, $beginsAt)) {
            return $this->asJson([
                "status" => "false",
                "message" => "Заполнены не все обязательные поля"
            ]);
        }

        $currency_id = Currency::getIdByCode(strtoupper($currency));
        if (empty($currency_id)) {
            return $this->asJson([
                "status" => "false",
                "message" => "Указан не существующий код валюты"
            ]);
        }

        if (is_string($officeId)) {
            $officeId = Office::getIdByCode($officeId);
            if (empty($officeId)) {
                return $this->asJson([
                    "status" => "false",
                    "message" => "Указан не существующий код офиса"
                ]);
            }
        }

        $rate = new Rate();
        try {
            $rate->currency_id = (int)$currency_id;
            $rate->buy = $buy;
            $rate->sell = $sell;
            $rate->begins_at = (new DateTime((string)$beginsAt))->getTimestamp();
            $rate->office_id = $officeId;
            $rate->created_at = time();
            if ($rate->save()) {
                return $this->asJson(["status" => "true"]);
            }
            return $this->asJson([
                "status" => "false",
                "message" => "Ошибка при сохранении данных"
            ]);
        } catch (Exception $ex) {
            return $this->asJson([
                "status" => "false",
                "message" => $ex->getMessage()
            ]);
        }
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function actionOfficeRates()
    {
        $request = Yii::$app->getRequest();
        $officeId = $request->bodyParams['office_id'];
        $atDate = $request->bodyParams['at_date'];

        if (!isset($officeId, $atDate)) {
            return $this->asJson([
                "status" => "false",
                "message" => "Заполнены не все обязательные поля"
            ]);
        }

        if (is_string($officeId)) {
            $officeId = Office::getIdByCode($officeId);
            if (empty($officeId)) {
                return $this->asJson([
                    "status" => "false",
                    "message" => "Указан не существующий код офиса"
                ]);
            }
        }
        try {
            $atDate = (new DateTime((string)$atDate))->getTimestamp();
        } catch (Exception $ex) {
            return $this->asJson([
                "status" => "false",
                "message" => $ex->getMessage()
            ]);
        }

        return $this->asJson(Rate::getRatesByOffice($officeId, $atDate));
    }
}