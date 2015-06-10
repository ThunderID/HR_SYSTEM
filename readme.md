## HR SYSTEM

## Updating

1. Composer update
2. Composer dump-autoload
3. Config database.php
3. Run php artisan migrate
4. Run php artisan db:seed


## Using Command in Controller
1. Running php artisan serv
2. Open localhost:8000
3. It is an example; usage of command in controller (see App/Http/Controllers/Organisation/OrganisationController.php)
4. Formatting of using command, see below :

### Getting

$this->dispatch(new Getting(new (Modelname), (array of search), (array of sort) , (current page), (take how much perpage)))

NOTES : how much per page has rule : 1 as first, 2 - 99 as get, 100 as all, if you leave it blank, it will initiate as 12

### Saving

$this->dispatch(new Saving(new {{Modelname}}, {{attributes in array}}, {{id of model, leave it null for new records}}, new {{RelationshipModel, do not initiate this if there is no relationship, only works for belongsto reltionship}}, {{Relationship model array, depend if you had relationship model or not}}, {{array of pivot data if only the relationship is belongstomany (using sync)}}))

### Deleting

$this->dispatch(new Deleting(new {{Modelname}}, {{itemid}}))
	
