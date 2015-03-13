# Front-Cheesy
Tiny PHP Framework for Front-end developers with easy routing!

## What is this?
A helpful framework based on PHP that helps you to create non-dynamic websites (there is no usage of databases)


### Install
- Download the project
- Copy to you project folder
- Edit: 'app/config.php' variable $path = '//localhost/myproject/'
- You are ready

### Folder Structure
- app/
  - controllers/
    - PagesController.php (default controller) - can edit
  - src
    - Route.php
    - App.php
  - config.php - can edit
  - helpers.php
  - routes.php - can edit
  - autoload.php
- views/
  - index.template.php
  - page.template.php
  - error.template.php
  - homepage/
    - navigation.html
  - layout/
    - header.html
    - footer.html
  - pages/
    - page-example.html

### Add new pages
#### Add new route
- Open 'app/routes.php'
