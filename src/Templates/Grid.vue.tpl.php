<template>
<div class="container wide">
    <div v-if="global_error_message" class="alert alert-danger" role="alert">
        {{ global_error_message }}
    </div>
    <div v-if="server_message !== false" class="alert alert-danger" role="alert">
        {{ this.server_message }} <a v-if="try_logging_in" href="/login">Login</a>
    </div>

    <!-- Grid Actions Top -->
    <div class="grid-top row align-items-start ">
        <div class="col-lg-3 pt-1 mb-3 mb-lg-0">
            <h1 class="fs-3 mb-0">[[display_name_plural]]</h1>
        </div>

        <div class="col-lg-6 mb-3 mb-lg-0">

            <!--   Search Filters       -->
            <form class="form-inline" aria-label="Data Filters" @submit.prevent="debounceKeywordGetData()">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="Search by Name"
                           aria-label="Search by Name"
                           v-model="keyword"
                           @keyup="debounceKeywordGetData()"
                           style="z-index:20;">
                    <div class="input-group-append position-relative" style="z-index:20;">
                        <span class="input-group-text p-0">
                            <a class="btn btn-primary" style="border-radius:0;"
                               @click.prevent="debounceKeywordGetData()"
                               href="#"
                               role="button"
                               id="submitKeywordSearch">
                                <svg role="img" aria-labelledby="title-submit-keyword-search"
                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff"
                                     class="bi bi-search" viewBox="0 0 16 16">
                                    <title id="title-submit-keyword-search">Submit keyword search</title>
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-3 text-start text-lg-end">
            <a href="#"

               @click.default="goToNew"
               class="btn btn-primary shadow-lg "
            >
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                     class="bi bi-plus" viewBox="0 0 16 16">
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                <span>
                        Add [[model_uc]]
                    </span>
            </a>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid no-more-tables table-responsive  mt-4 mb-3">
        <table class="table table-hover">
            <thead>
            <tr>
[[foreach:grid_columns]]
                <ss-grid-column-header
                    v-on:selectedSort="sortColumn"
                    v-bind:selectedKey="sort_column"
                    title="Sort by [[i.display]]"
                    style="width:auto;"
                    :params="{
                            sortField: '[[i.name]]',
                            InitialSortOrder: (filters.sort_column == '[[i.name]]' ? filters.sort_direction : 'asc'),
                        }">
                    [[i.display]]
                </ss-grid-column-header>
[[endforeach]]
                <th style="width:10%;" class="text-lg-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="gridState == 'wait'">
                <td colspan="6" class="grid-state">
                    <div class="alert alert-info text-center m-0 pt-5 pb-5"
                         role="alert">Please wait.
                    </div>
                </td>
            </tr>
            <tr v-if="gridState == 'error'">
                <td colspan="6" class="grid-state">
                    <div class="alert alert-warning text-center m-0 pt-5 pb-5"
                         role="alert">Error please try again.
                    </div>
                </td>
            </tr>

            <tr v-if="gridState == 'good' && !gridData.length">
                <td colspan="6" class="grid-state">
                    <div class="alert alert-warning text-center m-0 pt-5 pb-5"
                         role="alert">No matching records found.
                    </div>
                </td>
            </tr>

            <tr v-else v-for="row in this.gridData" :key="row.id">
                <td data-title="Name">
                    <a v-bind:href="'/[[route_path]]/' + row.id"
                       v-if="(permissions.can_show == '1')">
                        {{ row.name }}
                    </a>
                    <span v-if="(permissions.can_show != '1')">
                                        {{ row.name }}
                        </span>
                </td>
[[foreach:grid_columns]]
    [[if:i.name!='name']]
                <td data-title="[[i.display]]">{{ row.[[i.name]] }}</td>
    [[endif]]
[[endforeach]]
                <td data-title="Actions" class="text-lg-center text-nowrap">
                    <a v-bind:href="'/[[route_path]]/' + row.id + '/edit'"
                       v-if="(permissions.can_edit)"
                       class="grid-action-item">
                        <svg role="img" :aria-labelledby="'edit-icon-'+row.id" xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24"
                             fill="currentColor"
                             class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <title :id="'edit-icon-'+row.id">Edit {{ row.name }}</title>
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd"
                                  d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div><!-- end Grid -->

    <!-- Grid Actions Bottom -->
    <div class="grid-bottom row mb-4 align-items-center">
        <div class="col-lg-4 mb-2 mb-lg-0 d-print-none">
            <a href="/[[route_path]]/download" class="btn btn-outline-secondary mb-2 me-2">Export to
                Excel</a>
            <a href="/[[route_path]]/print" class="btn btn-outline-secondary mb-2 me-2">Print PDF</a>
        </div>
        <ss-grid-pagination class="col-lg-4 mb-2"
                            v-bind:current_page="current_page"
                            v-bind:last_page="last_page"
                            v-bind:total="total"
                            :page_links="page_links"
                            v-on:goto-page="getData">
        </ss-grid-pagination>
        <ss-grid-pagination-location class="col-lg-4 text-lg-right mb-2"
                                     v-bind:current_page="current_page"
                                     v-bind:last_page="last_page"
                                     v-bind:total="total">
        </ss-grid-pagination-location>
    </div><!-- end Grid Actions Bottom -->
</div>
</template>

<script>

import SearchTag from "../Base/SearchTag";

export default {
    name: '[[view_folder]]-grid',
    components: {SearchTag},
    props: {
        'filters': {
            type: Object,
            default: function () {
            }
        },
        'permissions': {
            type: Object,
            default: function () {
            }
        },
    },

    mounted: function () {

        this.current_page = (!isNaN(parseInt(this.filters.page))) ? parseInt(this.filters.page) : 1;
        this.keyword = this.filters.keyword;
        // set search filters to params or defaults

        this.getData(this.current_page);
    },

    data: function () {

        return {
            gridState: 'wait',
            keyword: this.filters.keyword,
            gridData: [],
            current_page: 1,
            last_page: null,
            total: null,
            page_links: [],

            sort_direction: this.filters.sort_direction,
            sort_column: this.filters.sort_column,

            global_error_message: null,


            form_errors: {
                page: false,
                keyword: false,
                sort_column: false,
                sort_direction: false,
            },
            server_message: false,
            try_logging_in: false,
        }
    },

    methods: {
        updateSearchData() {
            this.getData()
        },
        // Add a slight delay as user types so we're not pinging
        // the server every single keyup and potentially introducing race conditions:
        debounceKeywordGetData: function () {
            if (this.keywordTimeout) {
                clearTimeout(this.keywordTimeout);
            }
            this.keywordTimeout = setTimeout(() => {
                this.getData(1);
            }, 500);
        },

        goToNew: function () {
            window.location.href = '/[[route_path]]/create';
        },

        sortColumn: function (obj) {
            this.sort_column = obj.sortField;
            this.sort_direction = obj.sortOrder;
            this.getData(1);
        },

        getData: function (new_page_number = 1) {

            let searchParams = '';

            if (this.isDefined(this.keyword) && (this.keyword.trim().length > 0)) {
                let formattedSearchText = 'keyword=' + this.keyword
                searchParams = [searchParams, formattedSearchText].join('&')
            }
            console.log("searchParams", searchParams)

            this.global_error_message = null;

            let getPage;

            getPage = this.getDataUrl(new_page_number) +
                '&sort_column=' + this.sort_column +
                '&sort_direction=' + this.sort_direction;

            getPage += "&" + searchParams

            this.gridData = [];
            this.gridState = 'wait';

            if (getPage != null) {    // We have a filter
                axios.get(getPage)
                    .then(response => {
                        if (response.status === 200) {
                            Object.keys(this.form_errors).forEach(i => this.form_errors[i] = false);
                            this.gridData = response.data.data;
                            this.total = response.data.total;
                            this.current_page = response.data.current_page;
                            this.last_page = (response.data.last_page || 1);
                            this.page_links = response.data.links
                        } else {
                            this.server_message = res.status;
                        }
                        this.gridState = 'good';
                    }).catch(error => {
                    if (error.response) {
                        this.gridState = 'error';
                        if (error.response.status === 422) {
                            // Clear errors out
                            Object.keys(this.form_errors).forEach(i => this.form_errors[i] = false);
                            // Set current errors
                            Object.keys(error.response.data.errors).forEach(i => this.form_errors[i] = error.response.data.errors[i]);
                        } else if (error.response.status === 404) {  // Record not found
                            this.server_message = 'Record not found';
                            window.location = '/[[route_path]]';
                        } else if (error.response.status === 419) {  // Unknown status
                            this.server_message = 'Unknown Status, please try to ';
                            this.try_logging_in = true;
                        } else if (error.response.status === 500) {  // Unknown status
                            this.server_message = 'Server Error, please try to ';
                            this.try_logging_in = true;
                        } else {
                            this.server_message = error.response.data.message;
                        }
                    } else {
                        console.log(error.response);
                        this.server_message = error;
                    }
                });
            }
        },

        getDataUrl: function (new_page_number) {

            var url = 'api-[[route_path]]?';
            var queryParams = [];

            queryParams.push('page=' + new_page_number);

            if (queryParams.length > 0) url += queryParams.join('&');

            return url;
        }
    }
}

</script>
