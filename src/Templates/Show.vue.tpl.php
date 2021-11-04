<template>
<div class="container wide">

    <slot></slot>

    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="row mt-4 justify-content-center">

                <div class="col-md-6 mb-4 fixed-width-wrapping-column">
                    <h2 class="mb-3 text-primary">Billing address</h2>
                    <dl class="show-fields">
                    [[foreach:edit_columns]]
                    [[if:i.type!='id']]

                        <dt>[[i.display]]</dt>
                        <dd>
                            <dsp-text v-model="record.[[i.name]]"/>
                        </dd>

                    [[endif]]
                    [[endforeach]]

                    </dl>
                </div><!-- End fixed width column -->
            </div>
        </div>
    </div><!-- End centered columns -->

    <slot name="footer"></slot>


</div>
</template>

<script>

export default {
    name: "[[model_singular]]-show",
    props: {
        record: {
            type: [Boolean, Object],
            default: false,
        },
        csrf: {
            type: String,
        }
    },
};
</script>
