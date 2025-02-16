## Bulls-Media test task

#### Pre-installation actions:
1. Go to Google[ Cloud Console.](https://console.cloud.google.com/)
2. Create a new project or select already created.
3. Enable Google Sheets API and Google Drive API.
4. Create Service Account Credentials:
    * Go to APIs & Services → Credentials.
    * Click Create Credentials → Service Account.
    * Generate a JSON key file and download it.
5. Share your Google Sheet with the service account email
6. Move the credentials JSON file to `storage/app/google-credentials.json`
7. Make change in config `config/google_sheets.php`

#### Structure of config:
You can see structure in `config/google_sheets.php`
* `id` → id of spreadsheet
* `tabs` -> list of sheets in doc
* `name` -> tab name
* `db_table` -> DB table name where data should be stored
* `fields` -> list of fields
* `db_column` -> name of db field
* `db_type` -> type of field. Possible values: string,integer,decimal,datetime,text
* `date_format` -> format for datetime field
* `precision` -> format for decimal
* `additional_modifiers` -> array of db field modifiers. Possible values: nullable,unique,index

### Installation process:
Docker required. Checked on version 4.38
Installation process is pretty simple
1. `cp .env.example .env`
2. `make init`

### Import Google spreadsheet(s)
Run: `make import`
This command successfully install project via docker
Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

### Other usefully commands:
* `make start` - Start containers
* `make stop` - Stop containers
* `make sh` - Enter to container bash
* `make test` - Run phpunit tests
* `make generate-key` - Re-generate key
* `make remove-all-data` - Remove all containers and volumes
