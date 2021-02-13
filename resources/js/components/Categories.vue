<!-- Categories filter using select2 and API call. -->

<template>
    <select  name="select2" class="form-control" id="select2">
        <option value="" disabled>Select Category</option>
        <option value="">*</option>
    </select>
</template>
<script>
import Select2 from "select2";
export default {

    mounted:function() {
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
                            id:'*',
                            text:'*'
                        }].concat(data.data)
                    };
                }
            }
        })
            .val(this.value)
            .trigger('change')
            .on('change', function () {
                vm.$parent.fetchProducts($(this).val());
                vm.$parent.fetchProducts($(this).val());
            });
    }
}
</script>
