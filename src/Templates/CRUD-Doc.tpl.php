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
        Permission::findOrCreate('[[route_path]] index');
        Permission::findOrCreate('[[route_path]] show');
        Permission::findOrCreate('[[route_path]] print');
        Permission::findOrCreate('[[route_path]] export');
        Permission::findOrCreate('[[route_path]] create');
        Permission::findOrCreate('[[route_path]] edit');
        Permission::findOrCreate('[[route_path]] delete');
        Permission::findOrCreate('[[route_path]] history');
        Permission::findOrCreate('[[route_path]] history_difference');
```

From the bottom of the file, add these to admin

```
$permissions = $this->setRoles($permissions, '[[route_path]]', 'All');
```

From the bottom of the file, add these to read-only

```
$permissions = $this->setRoles($permissions, '[[route_path]]', 'Index');
```

Then run the following to install the permissions

```
php artisan app:set-user-permissions
```

### Filter By Organization

Clean up  App\Models\[[model_uc]]::buildBaseGridQuery()

```
$organization_id = \Auth::user()->organization_id;

if ($organization_id) {
$query->where('[[tablename]].organization_id', '=', $organization_id);
}
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

    Route::get('/api-[[route_path]]', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Api@index');
    Route::get('/api-[[route_path]]/options', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Api@getOptions');
    Route::get('/[[route_path]]/download', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@download')->name('[[route_path]].download');
    Route::get('/[[route_path]]/print', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@print')->name('[[route_path]].print');

    Route::get('/[[route_path]]/{[[model_singular]]}/history', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@history')->name('[[route_path]].history');
    Route::get('/[[route_path]]/{[[model_singular]]}/history/{history}', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller@historyDifference')
        ->name('[[route_path]].history-difference');

    Route::resource('/[[route_path]]', 'App\Http\Controllers\[[model_uc]]\[[model_uc]]Controller')
        ->missing(function () {
            session()->flash('flash_error_message', 'Cannot find the [[model_uc]].');
            return Redirect::route('[[route_path]].index');
        });


```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['[[route_path]] index'])
<li class="nav-item">
    <a class="nav-link @php if(isset($nav_path[0]) && $nav_path[0] == '[[route_path]]') echo 'active' @endphp"
       href="{{ route('[[route_path]].index') }}">[[display_name_plural]]
        @if(isset($nav_path[0]) && $nav_path[0] == '[[route_path]]') <span
            class="visually-hidden">(current)</span> @endif
    </a>
</li>
@endcan
```

##### Sub Menu

```

@can(['[[route_path]] index'])
<li>
    <a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == '[[route_path]]') echo 'active' @endphp"
       href="{{ route('[[route_path]].index') }}">
        [[display_name_plural]]
        @if(isset($nav_path[1]) && $nav_path[1] == '[[route_path]]') <span
            class="visually-hidden">(current)</span> @endif
    </a>
</li>
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
    :errors="form_errors.[[route_path]]_id">
    <ui-select-pick-one
        url="/api-[[route_path]]/options"
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
        url="/api-[[route_path]]/options"
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
$[[route_path]]_options = \App\[[model_uc]]::getOptions();
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
Vue.component('[[route_path]]', require('./components/[[route_path]].vue').default);
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
