<template>
    <div class="btn-group dropdown"
        :class="{' open' : OpenWhoInputGetter}" >
        <button 
            :disabled=" ! DisabledButton"
            v-tooltip.right-end="button.tooltip"
            @click="dropdown_open"
            :class="[
                    ( DisabledButton ) ? 'btn-color' : ''
                ]"
            class="btn btn-icon sq-32 dropdown-toggle"  type="button">
            <span class="material-icons">{{button.icon}}</span>
        </button>
        <ul class="dropdown-menu ">
            <li>
                <a href="#" @click.prevent=""  style="padding-bottom: 10px">
                    <div class="media">
                        <span class="material-icons">account_circle</span>
                        <span class="name">{{'title'}}</span>
                    </div>
                </a>
            </li>
            <li>
                <span class="text-center">
                    <form class="form form-inline" style="padding-bottom: 10px">
                        <div class="form-group">
                            <div class="input-with-icon">
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    placeholder="" 
                                    @focus="$event.target.select()"
                                    v-model="who"
                                >
                            </div>
                        </div>
                        <button
                            class="btn btn-primary btn-xs" 
                            :class="{'btn btn-primary btn-xs spinner spinner-inverse spinner-sm' : WhoSpinnerGetter}"
                            type="button" 
                            @click="changeStatus"
                            :disabled="! EnableSaveButton"
                        >Guardar</button>
                        <button 
                            class="btn btn-default btn-xs" 
                            type="button" 
                            @click="dropdown_open"
                        >Cancelar</button>        
                    </form>
                </span> 
            </li>
        </ul>
    </div>
</template>

<style scoped>
    .dropdown-menu{
        min-width : 360px !important;
    }
    ul.dropdown-menu {
        right: -281px;
    }
    div.media{
        display: flex;
    }
    div.media .name{
        margin-left: 1rem;
    }
    .btn-color{
        background-color: #bbe4e9;
        color : #606470
    }
</style>
