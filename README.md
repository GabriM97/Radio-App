# Radio.co app
## Build and Run the application

### Requirements
- **PHP 8.1** (temporary)
- **Docker** (and **docker compose**). [Get Docker here](https://docs.docker.com/get-docker/).
- **Composer** (temporary). [Get Composer here](https://getcomposer.org/download/).

### Start the application
The application will run on `localhost:8080`. You can change the port number from the `docker-file.yaml` file.
Run the following commands from the root of the project:
```sh
> composer install
> docker compose -f setup/docker-compose.yaml up --build
> php bin/console doctrine:migrations:migrate --no-interaction
```
Go to [http://localhost:8080](http://localhost:8080) to access the homepage.


## Application Endpoints
Following are some example requests for the available endpoints. 

### Webhook

**POST** /webhook
Webhooks entrypoint. Its behavior changes based on the event type passed. 
```json
{
    "type": "event.name",   // e.g. "episode.downloaded"
    "event_id": "uuid",
    "occurred_at": "ISO8601 date time",
    "data": {
        // e.g. payload for the "episode.downloaded" event
        "episode_id": "uuid",
        "podcast_id": "uuid"
    }
}
```
- `type` _(string)_ can be one of the following event types: `episode.downloaded`, ... more coming in the future.
- `event_id` _(uuid)_ the unique identifier of the event.
- `occurred_at` _(datetime)_ the time when the event happened in a ISO8601 date time format (e.g. `2022-11-15T18:10:34+0100`).
- `data` _(object)_ the JSON object containing the actual event data.


### Episode

**POST** /api/episode/create
Allows to create a new Episode. The response will contain the ID of the episode.
```json
{
    "title": "Episode Title",
    "topic": "Episode Topic",
}
```
- `title` _(string)_ the episode title
- `topic` _(string)_ the topic of the episode.

<br>

**GET** /api/episode/**{id}**/stats
Allows to retrieve stats (currently only the amount of downloads per day) for a specified Episode.

_Required_: 
- `id` _(int)_ the ID of the Episode you want to get stats for.

_Optional query parameters:_
- `last_days` _(?int)_ allows to retrieve stats for the last X days. Default: `7` days
- `from_date` _(?datetime)_ a ISO8601 date. If set, the endpoint will return the episode stats starting from the passed date. This field is used in combination with the `last_days` field when `to_date` is not set.
- `to_date` _(?datetime)_ a ISO8601 date. If set, the endpoint will return the episode stats up to the passed date. This field is used in combination with the `last_days` field when `from_date` is not set.
