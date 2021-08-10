<?php

require_once __DIR__.'/../require_tests_config.php';

class RegistrationSuccessConfirmationSuccessCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function registrationSuccessConfirmationSuccessTest(ApiTester $I)
    {
        // OTP Registration
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/registration', [
            "email" => "user8@test.com",
            "phoneNumber" => "3762222222",
            "password" => "ffff",
            RETURN_GENERATED_CONFIRMATION_CODE_KEY => RETURN_GENERATED_CONFIRMATION_CODE_STR,
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
            "confirmationCode" => $responseArr['response'][GENERATED_CONFIRMATION_CODE_KEY]
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"isSuccess":"1"');
        $I->seeResponseContains('"errors":""');
    }
}
