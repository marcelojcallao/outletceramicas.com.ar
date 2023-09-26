<template>
    <div id="header">
        <ul class="nav_c">
            <li class="title">Titulo</li>
            <li class="main_category" v-for="category in categories" :key="category.id">
                <a href="">{{category.name}}</a>
                <template  v-if="category.children.length">
                        <ul> 
                            <li v-for="cat in category.children" :key="cat.id"><a href="">submenu 1 <span>></span></a>
                                <ul>
                                    <li><a href="">aaa 1</a></li>
                                    <li><a href="">aaa 2</a></li>
                                    <li><a href="">aaa 3</a></li>
                                    <li><a href="">aaa 4</a></li>
                                </ul>
                            </li>
                        </ul>
                </template>
            </li>
            <li><a href="">solo el link 2</a></li>
            <li><a href="">solo el link 2</a></li>
            <li><a href="">solo el link 2</a></li>
            <!-- <li class="main_category"><a href="">Productos</a>
                <ul> 
                    <li><a href="">submenu 1 <span>></span></a>
                        <ul>
                            <li><a href="">aaa 1</a></li>
                            <li><a href="">aaa 2</a></li>
                            <li><a href="">aaa 3</a></li>
                            <li><a href="">aaa 4</a></li>
                        </ul>
                    </li>
                    <li><a href="">submenu 2</a>
                        <ul>
                            <li><a href="">bbb 1</a></li>
                            <li><a href="">bbb 2</a></li>
                            <li><a href="">bbb 3</a></li>
                            <li><a href="">bbb 4</a></li>
                        </ul>
                    </li>
                    <li><a href="">submenu 3</a></li>
                    <li><a href="">submenu 4</a></li>
                </ul>
            </li>
            <li class="main_category"><a href="">cccccccccccc</a>
                <ul> 
                    <li><a href="">submenu 1</a>
                        <ul>
                            <li><a href="">aaa 1</a></li>
                            <li><a href="">aaa 2</a></li>
                            <li><a href="">aaa 3</a></li>
                            <li><a href="">aaa 4</a></li>
                        </ul>
                    </li>
                    <li><a href="">submenu 2</a>
                        <ul>
                            <li><a href="">bbb 1</a></li>
                            <li><a href="">bbb 2</a></li>
                            <li><a href="">bbb 3</a></li>
                            <li><a href="">bbb 4</a></li>
                        </ul>
                    </li>
                    <li><a href="">submenu 3</a></li>
                    <li><a href="">submenu 4</a></li>
                </ul>
            </li> -->
        </ul>
    </div>
</template>

<script>

export default {
    
    name : 'www',

    props : {
        categories : {
            required : true
        }
    }
}
</script>

<style scoped>
    * {
        padding: 0px;
        margin: 0px;
    }
    #header{
        margin:auto;
        width: 20rem;
        font-family: "Hind", Verdana, Geneva, Tahoma, sans-serif
    }
    ul, ol {
        list-style: none;
    }
    .nav_c li a {
        background-color: black;
        color: aliceblue;
        text-decoration: none;
        padding: 1rem 1rem;
        display: block;
    }
    .nav_c li a:hover{
        background-color: #434343;
    } 
    .nav_c .main_category {
        text-transform : uppercase;
        display: flex;
        flex-direction: column;
        width: 10rem;
    }
    .nav_c li ul {
        display: none;
        position: absolute;
        min-width: 10rem;
        margin-left: 10rem;
    }
    .nav_c li:hover > ul {
        display: block;
    }
    .nav_c li ul li {
        position: relative;
        z-index: 999;
    }
    .nav_c li ul li ul {
        top:0px;
        z-index: 999;
    }
    a span {
        float: right;
        font-weight: bold;
    }
</style>