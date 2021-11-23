<template>
<!--Lisa Dropped form-->
<div class="container wide">
<!--    comment-->
    <form @submit.prevent="handleSubmit" @invalid.capture="handleInvalid" class="form-horizontal">

        <div v-if="server_message !== false" class="alert alert-danger" role="alert">
            <img src="/img/icons/danger.svg"
                 width="40" height="40"
                 class="align-middle d-inline-block"
                 alt="">
            <span class="align-middle d-inline-block">{{ this.server_message }} <a v-if="try_logging_in" href="/login">Login</a></span>
        </div>

        <slot></slot>

        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="row mt-4 justify-content-center">
                    <div class="col-md-6 mb-4 fixed-width-wrapping-column">
                        <fieldset>
                            <legend><h2 class="mb-0 text-primary">Contact</h2></legend>
[[foreach:edit_columns]]
                            <div class="row g-3">
[[if:i.name=='name']]
                                <div class="col-sm-12">
                                    <std-form-group label="Name" label-for="name" :errors="form_errors.name"
                                                    :required="true">
                                        <fld-input
                                            name="name"
                                            v-model="form_data.name"
                                            required
                                            aria-required="true"
                                            :errors="form_errors.name"
                                        />
                                        <template v-slot:help>
                                            Name must be unique.
                                        </template>
                                    </std-form-group>
                                </div>

[[endif]]
[[if:i.name!='name']]
                                <div class="col-sm-12">
                                    <std-form-group label="[[i.display]]" label-for="[[i.name]]" :errors="form_errors.[[i.name]]"
                                    >
                                        <fld-input
                                            name="[[i.name]]"
                                            v-model="form_data.[[i.name]]"
                                            aria-required="true"
                                            :errors="form_errors.[[i.name]]"
                                        />
                                    </std-form-group>
                                </div>


[[endif]]
                            </div>

[[endforeach]]
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <std-form-group label="Reason for Change" label-for="reason_for_change"
                                                    :errors="form_errors.reason_for_change"
                                                    :required="true">
                                        <fld-text-area
                                            name="reason_for_change"
                                            v-model="form_data.reason_for_change"
                                            required
                                            aria-required="true"
                                            :errors="form_errors.reason_for_change"

                                        />
                                    </std-form-group>
                                </div>
                            </div>
                        </fieldset>
                    </div><!-- End fixed width column -->

                </div>
            </div>
        </div><!-- End centered columns -->

        <div class="row mt-3">
            <div class="col-12 text-end  pe-5 d-print-none">
                <button type="submit" class="btn btn-primary" :disabled="processing">
                    <span v-if="this.form_data.id">Change [[model_uc]]</span>
                    <span v-else="this.form_data.id">Add [[model_uc]]</span>
                </button>
            </div>
        </div>
    </form>
</div>
</template>

<script>
import axios from 'axios';

export default {
    name: "[[view_folder]]-form",
    props: {
        record: {
            type: [Boolean, Object],
            default: false,
        },
        csrf_token: {
            type: String,
            default: ''
        },
    },
    data() {
        return {
            form_data: {
                // _method: 'patch',
                _token: this.csrf_token,
[[foreach:columns]]
    [[if:i.type=='id']]
                [[i.name]]: 0,
    [[endif]]
    [[if:i.type=='text']]
                [[i.name]]: '',
    [[endif]]
    [[if:i.type=='number]]
                [[i.name]]: 0,
    [[endif]]
    [[if:i.type=='date']]
                [[i.name]]: null,
    [[endif]]
    [[if:i.type=='unknown']]
                [[i.name]]: '',
    [[endif]]
[[endforeach]]
                reason_for_change: '',

            },
            form_errors: {
[[foreach:columns]]
                [[i.name]]: false,
[[endforeach]]
                reason_for_change: false,
            },
            server_message: false,
            try_logging_in: false,
            processing: false,
        }
    },
    mounted() {
        if (this.record !== false) {
            // this.form_data._method = 'patch';
            Object.keys(this.record).forEach(
                i => (this.form_data[i] = this.record[i])
            )
        } else {
            // this.form_data._method = 'post';
        }
    },
    methods: {
        handleInvalid(event) {
            /* Scroll to any html5 invalid elements */
            event.target.scrollIntoView({behavior: 'smooth'});
        },

        async handleSubmit() {

            this.server_message = false;
            this.processing = true;
            let url = '';
            let amethod = '';
            if (this.form_data.id) {
                url = '/[[route_path]]/' + this.form_data.id;
                amethod = 'put';
            } else {
                url = '/[[route_path]]';
                amethod = 'post';
            }
            await axios({
                method: amethod,
                url: url,
                data: this.form_data
            })
                .then((res) => {
                    if (res.status === 200 && res.data.message) {
                        window.location = '/[[route_path]]';
                    } else {
                        this.server_message = res.status;
                    }
                }).catch(error => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            // Clear errors out
                            Object.keys(this.form_errors).forEach(
                                i => (this.form_errors[i] = false)
                            );
                            // Set current errors
                            Object.keys(error.response.data.errors).forEach(
                                i => (this.form_errors[i] = error.response.data.errors[i])
                            );
                            this.server_message = "The given data was invalid. Please correct the fields indicated below.";
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
                    this.processing = false;
                    this.scrollToTop();
                });
        }
    },
}
</script>

