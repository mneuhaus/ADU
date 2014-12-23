Feature:
  In order to work with ADU
  As a user
  I need to be able to Log in

  Scenario: Login
  	Given I log in as "toni" with the password "tester"
    Then I should see an ".off-canvas" element

  Scenario: Let me know when i enter wrong credentials
  	Given I log in as "toni" with the password "wrong-password"
    Then I should see "Wrong username or password."

  Scenario: I need to be able to log out
  	Given I log in as "toni" with the password "tester"
  	Then I should see an "#user-menu" element
  	When I follow "user-menu"
  	And follow "logout"
  	Then I should see an ".form-signin" element