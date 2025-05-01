<?php
namespace contact;

use AcceptanceTester;
use Helper;

class FrontendInformationAcceptanceCest

{

    public $id;

    protected $data = [];

    public function _data()
    {
        $this->data['full_name'] = \Helper::faker()->name;
        $this->data['email'] = \Helper::faker()->email;
        $this->data['subject'] = \Helper::faker()->text(10);
        $this->data['description'] = \Helper::faker()->text;
       
        $this->data['mobile'] = \Helper::faker()->text(10);
      
    }

    public function _before(AcceptanceTester $I)
    {
       
    }

    public function _after(AcceptanceTester $I)
    {}
    /**
     * @group admin
     
     * @group guest
     */
    public function ContactUsWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Contact Us');
        $I->canSeeResponseCodeIs(200);
        $I->see('Contact Us', 'h1');
    }
    /**
     * @group admin
    
     * @group guest
     */
    public function ContactFormSubmittedEmpty(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Contact Us');
        $I->canSeeResponseCodeIs(200);
        $I->click('Send Message');
        $I->canSeeResponseCodeIs(200);
        $I->expectTo('see validations errors');
        $req = $I->grabMultiple('.required');
        $count = count($req)+1;
        $I->seeNumberOfElements('.has-error', $count);
    }
   
    /**
     * @group guest
     */
   public function AddWorksWithData(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Contact Us');
        $I->canSeeResponseCodeIs(200);
        $I->fillField('Information[full_name]', \Helper::faker()->name);
        $I->fillField('Information[email]', \Helper::faker()->email);
        $I->fillField('Information[subject]', \Helper::faker()->text(10));
        $I->fillField('Information[description]', \Helper::faker()->text);
        $I->fillField('Information[mobile]', \Helper::faker()->phoneNumber);
        $I->click('Send Message');
        $I->canSeeResponseCodeIs(200);
    } 
    /**
     * @group guest
   
     */
    public function ThankyouWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/contact/information/thankyou');
        $I->amGoingTo('check  Thankyou works ');
        $I->canSeeResponseCodeIs(200);
    }
    
  
}