Feature:
  In order to work safely
  As a Administrator, Kundenberater and Bereichsleiter
  I should only see what i need

  Scenario: Logged in as Administrator
  	Given I log in as "max" with the password "tester"
    Then I should see an ".off-canvas" element
    And I should see "Befragungen"
    And I should see "Durchführen"
    And I should see "Selbsteinschätzung"
    And I should see "Auswertung"
    And I should see "Niederlassungen"
    And I should see "Kategorien"
    And I should see "Kunden"
    And I should see "Nutzermanagement"

  Scenario: Logged in as Bereichsleiter
    Given I log in as "hans" with the password "tester"
    Then I should see an ".off-canvas" element
    And I should see "Befragungen"
    And I should see "Durchführen"
    And I should see "Selbsteinschätzung"
    And I should see "Auswertung"
    And I should see "Nutzermanagement"
    And I should not see "Niederlassungen"
    And I should not see "Kategorien"
    And I should not see "Kunden"

  Scenario: Logged in as Kundenberater
    Given I log in as "toni" with the password "tester"
    Then I should see an ".off-canvas" element
    And I should see "Befragungen"
    And I should see "Durchführen"
    And I should see "Selbsteinschätzung"
    And I should see "Auswertung"
    And I should not see "Niederlassungen"
    And I should not see "Kategorien"
    And I should not see "Kunden"
    And I should not see "Nutzermanagement"