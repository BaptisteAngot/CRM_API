# CRM_API
A School Project, make an CRM with Symfony
Requirements :

Prerequisites for the proper functioning of the application

    PHP 7.2
    Composer
    Symfony 5.2

Installation
Get the project :

    git clone : https://github.com/BaptisteAngot/CRM_API.git

Install bundles with composer :

    composer install

Create an .env.local, add your database URL

    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

Create your MySQL database with the following commands:

    php bin/console make:migration

    php bin/console doctrine:migrations:migrate
 
Add JsonWebToken:

    Create private key and public key with pass prase 
      add the keys in folders jwt and put the file to config file 
      config>jwt>public.pem
                 private.pem
      next add your keys and pass phrase in your .env.local: 
      
      JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
      JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
      JWT_PASSPHRASE=passphrase
 
 
start your server:

  symfony server:start

Route:

  -------------------------- --------  -----------------------------------    
    Name                       Method      Path                                   
   -------------------------- -------- -----------------------------------    
    _preview_error             ANY          /_error/{code}.{_format}            
    _wdt                       ANY          /_wdt/{token}
    _profiler_home             ANY          /_profiler/
    _profiler_search           ANY          /_profiler/search
    _profiler_search_bar       ANY          /_profiler/search_bar
    _profiler_phpinfo          ANY          /_profiler/phpinfo
    _profiler_search_results   ANY          /_profiler/{token}/search/results
    _profiler_open_file        ANY          /_profiler/open
    _profiler                  ANY          /_profiler/{token}
    _profiler_router           ANY          /_profiler/{token}/router
    _profiler_exception        ANY          /_profiler/{token}/exception
    _profiler_exception_css    ANY          /_profiler/{token}/exception.css
    client                     ANY          /api/client/client
    create_client              POST         /api/client/create
    update_client              PUT          /api/client/update
    get_AllClient              GET          /api/client/getAll
    disable_client             PUT          /api/client/disable
    mail                       POST         /api/mail/send
    mail_prospect              POST         /api/mail/prospect
    mail_client                POST         /api/mail/client/{id}
    mail_client_relance        POST         /api/mail/client/relance
    mail_client_new            POST         /api/mail/client/new
    origine_add                POST         /api/origine/add
    get_all_origine            GET          /api/origine/all
    get_one_origine            GET          /api/origine/{id}
    update_origine             PUT          /api/origine/update/{id}
    delete_origine             DELETE       /api/origine/delete/{id}
    prospect_add               POST         /api/prospect/add
    get_all_prospect           GET          /api/prospect/all
    get_one_prospect           GET          /api/prospect/{id}
    update_prospect            PUT          /api/prospect/update/{id}
    delete_prospect            DELETE       /api/prospect/delete/{id}
    api_login                  POST         /login
    app_logout                 GET          /logout
    user_profil                POST         /api/user/userProfil
    create_user                POST         /api/user/create
    update_user                PUT          /api/user/update
    get_AllUsers               GET          /api/user/getAll
    disabled_user              PUT          /api/user/disable
   -------------------------- --------  -----------------------------------



Create Fixture(add one User for each Role, add two Origine and one Prospect) with bundle fixture:
    
    php bin/console doctrine:fixtures:load
    
 Create Users Admin:
    
    php bin/console create:admin:user
    
    
  
 
