# Change Requests

## Retrieving Change Request Information
CR information may be retrieved using the `cr->get("<CR ID>")` method on the `Remedy\RemedyClient`:
```php
$cr = $remedyClient->changeRequests->get("CRQ000001234567");

// Interact with CR data
$cr->id;
// => "CRQ000001234567"

$cr->summary;
// => "My CR summary!"
```
More information on available CR fields can be found in the documentation at https://pages.github.cerner.com/CWxAutomation/php_remedy/classes/Remedy/CRQ/ChangeRequest.html.

CR information may be retrieved using the `cr->getByUtn("<CR UTN ID>")` method on the `Remedy\RemedyClient`:
```php
$crUtn = $remedyClient->changeRequests->getByUtn("123456789");

// Data response returned from client. CRQ data located in content as an array.
$crUtn->content;
$firstCRQ = $crUtn->content[0];

```
## Creating a Change Request
A new change request may be created by initializing a `Remedy\CRQ\ChangeRequest` object with the `Remedy\RemedyClient` and providing the necessary minimum details.
```php
$cr = new Remedy\CRQ\ChangeRequest($remedyClient);

$cr->coordinator->loginId = 'ab012345';
$cr->coordinator->group = 'CWX_TECH_IMPROVEMENT';
$cr->manager->loginId = 'cd123456';
$cr->manager->group = 'CWX_TECH_IMPROVEMENT';
$cr->summary = 'test CR...';
$cr->contact = 'ab012345';
$cr->template = "CWx_Standard Refresh Domain";
$cr->scheduledStartDate = Carbon\Carbon::now()->addDays(1);
$cr->scheduledEndDate = Carbon\Carbon::now()->addDays(7);
$cr->businessServices = "ABC_DE";
$cr->computerSystems = ["abcdeapp1.domain.com", "abcdeapp2.domain.com"];
$cr->save();

// The CR will now be populated with a CRQ number:
$cr->id;
// => "CRQ000001234568"

// Reload the CR from Remedy:
$cr->fresh();
```
> If the coordinator and manager groups are not set before creating the CR, the CR client will attempt to automatically determine them from Remedy.

## Modifying a Change Request
An existing CR object may be modified by changing various properties and calling the `save()` method.
```php
$cr = $remedyClient->changeRequests->get("CRQ000001234567");
$cr->summary = "A new summary!";
$cr->save();

$cr->actualStartDate = Carbon\Carbon::now();
$cr->save();
```

### Modifying Status
You may also change the status of the CR using several helper methods:
```php
// Transition to Planning In Progress
$cr->toPlanningInProgress();

// Transition to Scheduled status (if approval is needed, the CR will stop at Scheduled For Approval)
$cr->toScheduled();

// Transition to Implementation In Progress status
// This automatically populates the actualStartDate from the current date and time
$cr->toImplementationInProgress();

// Transition to Completed status
// This automatically populates the actualEndDate from the current date and time
$cr->toCompleted();

// Transition to Cancelled status
$cr->toCancelled();

// Transition to Draft status
$cr->toDraft();
```

### Adding Worklogs
Worklogs may also be added to an existing CR:
```php
// Populate the Worklog object
$worklog = new Remedy\CWL\ChangeWorklog();
$worklog->summary = "My worklog";
$worklog->notes = "My worklog notes";
$worklog->type = "Work Plan";

// Add an attachment from the local filesystem
$worklog->addAttachment("/path/to/my/file.txt");

// Add the worklog to the CR
$cr->addWorklog($worklog);
```
