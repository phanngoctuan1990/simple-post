<template>
    <v-snackbar
        v-model="display"
        :color="color"
        :timeout="2000"
        :top="true"
        :center="true"
    >
        {{ message }}
       
    </v-snackbar>
</template>

<script>
export default {
    props: ['message', 'color', 'show'],
    computed: {
        display: {
            get: function(){
                return this.show
            },
            set: function(newValue){
                this.$emit('update:show', newValue)
            } 
        }
    }
}
</script>
