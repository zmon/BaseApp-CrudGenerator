# [[model_uc]] - `[[model_plural]]`

## To create or replace missing CRUD

Updated for Laravel 7

```
php artisan make:crud [[model_plural]]  --display-name="[[display_name_plural]]" --grid-columns="name"   # --force --skip-append
```

You will want to adjust the grid-columns to add more columns  for example to add alias

```
--grid-columns="name:alias"
```

To replace one file, remove it and rerun the above command

To replace all files, uncomment `--force`


## After running Crud Generator


#### Setup Permissions in `app/Lib/InitialPermissons.php`

From the bottom of the file put these at the top in alpha order

```
        Permission::findOrCreate('[[model_singular]] index');
        Permission::findOrCreate('[[model_singular]] view');
        Permission::findOrCreate('[[model_singular]] export-pdf');
        Permission::findOrCreate('[[model_singular]] export-excel');
        Permission::findOrCreate('[[model_singular]] add');
        Permission::findOrCreate('[[model_singular]] edit');
        Permission::findOrCreate('[[model_singular]] delete');
```

From the bottom of the file, add these to admin

```
'[[model_singular]] index',
'[[model_singular]] view',
'[[model_singular]] export-pdf',
'[[model_singular]] export-excel',
'[[model_singular]] add',
'[[model_singular]] edit',
'[[model_singular]] delete',
```

From the bottom of the file, add these to read-only

```
        '[[model_singular]] index',
        '[[model_singular]] view',
```

Then run the following to install the permissions

```
php artisan app:set-user-permissions
```

### Components

In `resource/js/components`


Add

```
[[model_uc]]Grid: defineAsyncComponent(() => import(/* webpackChunkName:"[[model_uc]]Grid" */ "./components/[[model_uc]]/[[model_uc]]Grid")),
[[model_uc]]GridAdvanced: defineAsyncComponent(() => import(/* webpackChunkName:"[[model_uc]]GridAdvanced" */ "./components/[[model_uc]]/[[model_uc]]GridAdvanced")),
[[model_uc]]Form: defineAsyncComponent(() => import(/* webpackChunkName:"[[model_uc]]Form" */ "./components/[[model_uc]]/[[model_uc]]Form")),
[[model_uc]]Show: defineAsyncComponent(() => import(/* webpackChunkName:"[[model_uc]]Show" */ "./components/[[model_uc]]/[[model_uc]]Show")),

```

### Routes

In `routes/web.php


Add

```
    ///////////////////////////////////////////////////////////////////////////////
    // [[display_name_plural]]
    ///////////////////////////////////////////////////////////////////////////////

    Route::get('/api-[[model_singular]]', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Api@index');
    Route::get('/api-[[model_singular]]/options', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Api@getOptions');
    Route::get('/[[model_singular]]/download', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@download')->name('[[model_singular]].download');
    Route::get('/[[model_singular]]/print', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@print')->name('[[model_singular]].print');

    Route::get('/[[model_singular]]/{[[model_singular]]}/history', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@history')->name('[[model_singular]].history');
    Route::get('/[[model_singular]]/{[[model_singular]]}/history/{history}', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@historyDifference')
        ->name('[[model_singular]].history-difference');

    Route::resource('/[[model_singular]]', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller')
        ->missing(function () {
            session()->flash('flash_error_message', 'Cannot find the [[model_uc]].');
            return Redirect::route('[[model_singular]].index');
        });
        
        
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['[[model_singular]] index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == '[[model_singular]]') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('[[model_singular]].index') }}">[[display_name_plural]] <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['[[model_singular]] index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == '[[model_singular]]') echo 'active' @endphp"
   href="/[[model_singular]]">[[display_name_plural]]</a>
@endcan
```



## Code Cleanup


```
app/Exports/[[model_uc]]Export.php
app/Http/Controlers/[[model_uc]]Controler.php
app/Http/Controlers/[[model_uc]]Api.php
app/Http/Requests/[[model_uc]]FormRequest.php
app/Http/Requests/[[model_uc]]IndexRequest.php
app/Lib/Import/Import[[model_uc]].php
app/Observers/[[model_uc]]Observer.php
app/[[model_uc]].php
resources/js/components/[[model_plural]]resources/views/[[model_plural]]
node_modules/.bin/prettier --write resources/js/components/[[model_plural]]/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/[[model_plural]]/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/[[model_plural]]/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="[[model_uc]]"
    label-for="[[model_singular]]_id"
    :errors="form_errors.[[model_singular]]_id">
    <ui-select-pick-one
        url="/api-[[model_singular]]/options"
        v-model="form_data.[[model_singular]]_id"
        :selected_id="form_data.[[model_singular]]_id"
        name="[[model_singular]]_id"
        :blank_value="0">
    </ui-select-pick-one>
</std-form-group>


import UiSelectPickOne from "../SS/UiSelectPickOne";

components: { UiSelectPickOne },
```

## GRID Vue Component example

```
<search-form-group
    class="mb-0"
    label="[[model_uc]]"
    label-for="[[model_singular]]_id"
    :errors="form_errors.[[model_singular]]_id">
    <ui-select-pick-one
        url="/api-[[model_singular]]/options"
        v-model="form_data.[[model_singular]]_id"
        :selected_id="form_data.[[model_singular]]_id"
        name="[[model_singular]]_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$[[model_singular]]_options = \App\[[model_uc]]::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => '[[model_singular]]_id',
'selected_id' => $RECORD->[[model_singular]]_id,
'first_option' => 'Select a [[display_name_plural]]',
'options' => $[[model_singular]]_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components

 In `resource/js/components`

Remove

```
Vue.component('[[model_singular]]', require('./components/[[model_singular]].vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/[[model_uc]]Query.php
rm resources/js/components/[[model_uc]]Grid.vue
```


#### Remove from routes

```
Route::get('api/owner-all', '\\App\Queries\GridQueries\OwnerQuery@getAllForSelect');
Route::get('api/owner-one', '\\App\Queries\GridQueries\OwnerQuery@selectOne');
```

#### Remove the Grid Method
vi app/Http/Controllers/ApiController.php


```
// Begin Owner Api Data Grid Method

public function ownerData(Request $request)
{

return GridQuery::sendData($request, 'OwnerQuery');

}

// End Owner Api Data Grid Method
```
