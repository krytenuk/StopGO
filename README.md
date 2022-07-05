Endpoint can be reached at https://your-domain.com/endpoint.php?cn=cardnumberid

Test data can be found in "Test Data.csv" and the database schema is in "database.sql"

The employee uses their card to enter the company building, I assume the employee will be checked and only allowed access to the building by another API as is is not in this scope. The above API endpoint should be called and if the card is valid and linked to the employee the employee’s full name and departments will be returned and the employee is given a personalised greeting. On failure the full name and departments will be empty. 

There is a unit test the check the Db class's "checkRfidCard" method. I also tested endpoint in "Insomnia" with a valid card id "142594708f3a5a3ac2980914a0fc954f" and an invalid card number.

This is just a crude implementation which could do with some security additions such as preventing brute force attacks and maybe checking the IP address of the request and rejecting it if it is from a country where the company has no buildings, although this could be circumvented by a VPN.

The endpoint will return the required data but has no way of knowing if it’s the correct building being accessed, this was not specified in the requirements.

