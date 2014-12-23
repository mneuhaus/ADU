Feature:
  In order to manage Users
  As a administrator
  I need to be to create, update and delete users

  Scenario: Login
    Given I log in as "toni" with the password "wrong-password"
    Then I should see an ".off-canvas" element

  Scenario: Let's create a new User
    Given I am on homepage
    When I follow "Nutzermanagement"
    When I follow "Neu"
    Then I should see an ".t3-expose-form" element