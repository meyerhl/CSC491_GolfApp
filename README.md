# CSC491_GolfApp
Code for a golf application using a dynamic website

This section documents the features, functions, conditions, or capabilities required for this project based on requests 
from the users, and what criteria the requirement must exhibit to the user for the requirement to be considered accepted.

Requirement Description	Criteria for Acceptance:
1. A function for registration and login to the site- There must be a button or link that allows user to login or register
   themselves in the system.
2. A capability to store user’s name, generated username and email- Registered users must be able to see their name at login
   which will verify the database is returning the right user data.
3. A feature that allows registered user to select a golf course- User can select the appropriate course they wish to play
   rather than typing in a course name.
4. A capability to store course details including, but not limited to: rating, slope, name, the holes, par, etc.- Registered
   users must be able to select a course name which pulls in data about the course which will verify the database is returning
   the correct data.
5. A function that calculates handicap for the registered user- A DBA must write the mathematical computation that takes the best
    8 games for any registered user and calculates the handicap based on entered scores.
6. A function that sends a notification to users of their handicap score- Users will receive a notification after entry of their
    first 8 games, and after every future submission of a scorecard as their new handicap is calculated.
7. A function that displays a scorecard to a registered user- User must be able to enter scores on the scorecard, and a submit
   button to record entry of those scores. Scores must be added to the user's history so a handicap can be calculated on the
   best 8 games.
8. A function that displays which holes get extra strokes- Users need to have a visual indicator of which holes they get to
    take extra strokes based on their calculated handicap.
9. A function that displays the winner of the round- Users will see the name of the winner on the
    screen after the submit scorecard button is pressed.

Specifications Documentation

3.1 Overview of Use Cases

This document is intended to explain the functions, features and capabilities of the Golf Scorecard Application, going forward 
referred to as Use Cases. These requirements can be reviewed in the chart on page 5 of this document. Further, this section will 
outline the step-by-step description of how the requirement is intended to work based on user input.

3.2 Login or Register Use Case

1.	The user will press a button called “Login/Register”. This button takes the user to a separate page within the website structure.
2.	On the Login/Register page, users will be presented with fields to enter first name, last name, email, and a chosen username. 
3.	At the end of the data entry form, users will press either the “Login” button (if they are a registered user) or “Sign Me Up” button
   if they want to be a registered user.
4.	Users that click the “Login” button will be taken to a welcome page where it displays their name, as well as the options available
   to registered users. The feature available to registered users is to “Start a Game” (see 3.3 below).
5.	Users that click the “Sign Me Up” button will get a visual confirmation on the page letting them know they are registered. They will
   then be redirected with their new credentials to the same page as registered users, and can see the “Start a Game” function (see 3.3 below). 

3.3 Select Course Use Case

1.	Registered users who have entered their username into the system will be presented an option to press a button called “Start a Game”.
   This button takes the registered user to a separate page within the website structure.
2.	On the Start a Game page, registered users will be presented with four drop-down menus from which to select the following: a course name,
   a tee type, the holes they wish to play, and the number of players in the group.  
3.	Gender is collected in the registration process because it is used on course ratings and slope. Once the data is entered, the details of
   the course selected will be retrieved from the database and presented to the user on the same page as a verification of the course selected,
  	based on gender of the user. 
4.	Registered user will press a button called “Scorecard”. This feature is explained in 3.5.

3.4 Calculate Handicap Use Case

1.	Handicap score is calculated using an algorithm that calculates the total number of strokes over par for 54 holes, and the average of those
   scores represents the initial user’s handicap. Scores for the 54 holes must be recorded in the system to generate a handicap.
2.	As registered users complete additional rounds and submit their scores into the system, the handicap is recalculated. 
3.	In this recalculation process, an email notification will be sent to the user any time the handicap score is recalculated. The email will be
   generated using data from the database.
4.	Registered users who press the “Start a Game” button from above (see 3.3) will be shown their current handicap score.

3.5 Display and Edit Scorecard Use Case

1.	See feature 3.3 above where user selected the course in which to play. After the registered user presses the button called “Scorecard”,
   registered users are directed to a separate page within the website structure.
2.	On the Scorecard page, the registered user will see a scorecard layout based on the number of holes selected, and the number of players chosen.
3.	The registered user’s username will appear in the top row as the first player of the card. The card feature will also display the Par for each
   hole, the Par for each set of 9 holes, and the total Par for the course. This data is based on information collected in the system for the hole selected.
4.	As the round of game progresses, user will be able to enter individual hole scores for each player on the card.
5.	At the end of the round, user will press a “Submit Scores” button to commit the scores to the database for the user. 

3.6 Extra Stroke Indicator Use Case

1.	Extra stroke values are calculated based on the handicap score of a registered user, as well as the index of a hole, if the course has provided
   that detail. Therefore, this feature is available on the Scorecard page to the registered user who has met the requirement to have a generated handicap
    recorded in the system (see 3.4 above). 
2.	To the right of the scorecard, the registered user will be able to see the number of strokes that can be taken for the current unscored hole. 

3.7 Display Winner Use Case

1.	From the Scorecard page, and after the user presses the “Submit Scores” button (see 3.5 above), the system will evaluate and calculate all
   scores entered for the users on the scorecard. 
2.	The calculation is to determine who is least over par, given the handicap(s) of any registered users. The winner is determined to be the
   person who has the fewest strokes over par.
3.	A visual indicator showing the user’s name will highlight who is the winner of the round.
