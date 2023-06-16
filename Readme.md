### PlayerTeam app

###### This project is a Symfony skills test.

To run the project, you need to have Docker and Docker Compose installed on your machine.

Run `docker-compose up --build -d`
Go to [http://localhost:8888](http://localhost:8888)

What I did:

1. Custom REST API (backend) with Serialization/deserialization concept for handling data.
2. Data validation and one voter for setting player worth.
3. Some API routes are protected with an api firewall.
4. I had to implemented authentication with JWT authentication for buy and sell players use case.
5. For the frontend, ReactJS is used, embedded in the project thanks to Symfony UX.
