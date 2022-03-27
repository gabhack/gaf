<template>
    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="menu1-tab" data-toggle="tab" href="#menu1" role="tab" aria-controls="menu1" aria-selected="true">Menu 1</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="menu2-tab" data-toggle="tab" href="#menu2" role="tab" aria-controls="menu2" aria-selected="false">Menu 2</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="menu3-tab" data-toggle="tab" href="#menu3" role="tab" aria-controls="menu3" aria-selected="false">Menu 3</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="menu4-tab" data-toggle="tab" href="#menu4" role="tab" aria-controls="menu4" aria-selected="false">Menu 4</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                Menu 1
            </div>
            
            <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                Menu 2
            </div>
            <div class="tab-pane fade" id="menu3" role="tabpanel" aria-labelledby="menu3-tab">
                Menu 3
            </div>
            <div class="tab-pane fade" id="menu4" role="tabpanel" aria-labelledby="menu4-tab">
                Menu 4
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tabView:1
        }
    },
    methods:{
        changeViewMenu(data){
            this.tabView = data;
        }
    }
}
</script>