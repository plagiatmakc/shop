<template>
    <!-- Modal Create SubCategory -->
    <div id="SubCategory" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modal Header</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <category-create v-bind:parent_id="parent_id"></category-create>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props:['parent_id'],
        name: "ModalCreateCategory"
    }
</script>

<style scoped>

</style>
