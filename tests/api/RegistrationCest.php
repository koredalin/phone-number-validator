<?php

require_once __DIR__.'/../require_tests_config.php';

class RegistrationCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function registrationSuccessWrongConfirmationCodeTest(ApiTester $I)
    {
        // OTP Registration
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/registration', [
            "email" => "user8@test.com",
            "phoneNumber" => "3762222222",
            "password" => "ffff",
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"isSuccess":"1"');
        $I->seeResponseContains('"errors":""');
        
        // OTP Phone Number Confirmation
        $responseArr = \json_decode($I->grabResponse(), true);
        $nextWebPage = $responseArr['arguments']['nextWebPage'] ?? '';
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost($nextWebPage, [
            "confirmationCode" => "333"
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"isSuccess":""');
        $I->seeResponseContains('"errors":"Wrong confirmation code."');
    }
}
