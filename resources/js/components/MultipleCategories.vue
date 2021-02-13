<!-- Categories filter using multiple select2 and API call for product's form. -->

<template>
    <select name="select2" class="form-control" id="select2-multiple" multiple="multiple">
    </select>
</template>
<script>
import Select2 from "select2";

export default {

    mounted: function () {
        var vm = this;
        $(this.$el).select2({
            allowClear: true,
            theme: 'bootstrap4',
            data: this.options,
            ajax: {
                url: 'api/categories/search',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: [{
                            id: '*',
                            text: '*'
                        }].concat(data.data)
                    };
                }
            }
        })
            .val(this.value)
            .trigger('change')
            .on('change', function () {
                vm.$emit('input', $(this).val());
            });
    }
}
</script>
