<?php
class Example extends PHPUnit_Extensions_SeleniumTestCase
{
  public static $browsers = array(
      array(
        'name'    => 'Firefox on Win',
        'browser' => '*firefox',
        'host'    => 'localhost',
        'port'    => 4444,
        'timeout' => 30000,
      ),
      array(
        'name'    => 'IE Win7',
        'browser' => '*iexplore',
        'host'    => 'localhost',
        'port'    => 4444,
        'timeout' => 30000,
      )
    );
  protected function setUp()
  {
    $this->setBrowserUrl(HOST);
  }

  public function testMyTestCase()
  {
    $this->open("/wordpress/wp-login.php");
    $this->type("id=user_login", "user");
    $this->type("id=user_pass", "bitnami");
    $this->click("id=wp-submit");
    $this->waitForPageToLoad("30000");
  }
}