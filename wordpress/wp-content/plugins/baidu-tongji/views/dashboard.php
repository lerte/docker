<script src="http://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>

<style type="text/css">
.tongji-main{
    background-color: #ececec;
}
.tongji-content{
    padding: 20px;
    background-color: #fff;
}
.tongji-content .table{
    width: 100%;
    line-height: 2;
    border-collapse: collapse;
}
table>thead>tr>td, table>thead>tr>th{
    border: 1px solid #ddd;
    border-bottom-width: 2px;
}
table>tbody>tr:hover{
    background-color: #f5f5f5;
}
table td{
    border: 1px solid #ededef;
}
.visit-type-new .visit-type-summary-data{
    color: #48cb6d;
    font-size: 30px;
}
.visit-type-new .ratil-unit{
    font-size: 18px;
}
.visit-type-old .visit-type-summary-data{
    color: #51a8f9;
    font-size: 30px;
}
.visit-type-old .ratil-unit{
    font-size: 18px;
}
</style>
<div id="tongji-app" class="wrap">
    <div class="tongji-main">
        <div class="tongji-content">
            <h2 class="title">今日流量</h2>
            <table class="table" v-if="TrendTimeA">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>浏览量(PV)</td>
                        <td>访客数(UV)</td>
                        <td>IP数</td>
                        <td>跳出率</td>
                        <td>平均访问时长</td>
                        <td>转化次数</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="highlight">
                        <td>今日 ({{trendTime.items[0][0][0]}})</td>
                        <td>{{trendTime.items[1][0][0]}}</td>
                        <td>{{trendTime.items[1][0][1]}}</td>
                        <td>{{trendTime.items[1][0][2]}}</td>
                        <td>{{trendTime.items[1][0][3]}}</td>
                        <td>{{trendTime.items[1][0][4] | toMinutes}}</td>
                        <td>{{trendTime.items[1][0][5]}}</td>
                    </tr>
                    <tr>
                        <td>昨日 ({{trendTime.items[0][1][0]}})</td>
                        <td>{{trendTime.items[1][1][0]}}</td>
                        <td>{{trendTime.items[1][1][1]}}</td>
                        <td>{{trendTime.items[1][1][2]}}</td>
                        <td>{{trendTime.items[1][1][3]}}</td>
                        <td>{{trendTime.items[1][1][4] | toMinutes}}</td>
                        <td>{{trendTime.items[1][1][5]}}</td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">地域分布</h2>
            <table class="table" v-if="DistrictRpt">
                <thead>
                    <tr>
                        <td>省份</td>
                        <td>浏览量(PV)</td>
                        <td>占比</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in district.items[0]" :key="index">
                        <td>{{item[0]}}</td>
                        <td>{{district.items[1][index][0]}}</td>
                        <td>{{district.items[1][index][1]}}</td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">Top10搜索词</h2>
            <table class="table" v-if="CommonTrackRpt">
                <thead>
                    <tr>
                        <td class="al url">搜索词</td>
                        <td class="ar" width="20%">浏览量(PV)</td>
                        <td class="ratio" width="20%">占比</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in word.items" :key="index">
                        <td :title="item[0]" v-text="item[0]">
                        </td>
                        <td v-text="item[1]"></td>
                        <td v-text="`${item[2]}%`"></td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">Top10来源网站</h2>
            <table class="table" v-if="CommonTrackRpt">
                <thead>
                    <tr>
                        <td class="al url">来源网站</td>
                        <td class="ar" width="20%">浏览量(PV)</td>
                        <td class="ratio" width="20%">占比</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in sourceSite.items" :key="index">
                        <td :title="item[0]" v-text="item[0]">
                        </td>
                        <td v-text="item[1]"></td>
                        <td v-text="`${item[2]}%`"></td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">Top10入口页面</h2>
            <table class="table" v-if="CommonTrackRpt">
                <thead>
                    <tr>
                        <td class="al url">入口页面</td>
                        <td class="ar" width="20%">浏览量(PV)</td>
                        <td class="ratio" width="20%">占比</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in landingPage.items" :key="index">
                        <td :title="item[0]">
                        <a target="_blank" :href="item[0]" v-text="item[0]"></a>
                        </td>
                        <td v-text="item[1]"></td>
                        <td v-text="`${item[2]}%`"></td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">Top10受访页面</h2>
            <table class="table" v-if="CommonTrackRpt">
                <thead>
                    <tr>
                        <td class="al url">受访页面</td>
                        <td class="ar" width="20%">浏览量(PV)</td>
                        <td class="ratio" width="20%">占比</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in visitPage.items" :key="index">
                        <td :title="item[0]">
                        <a target="_blank" :href="item[0]" v-text="item[0]"></a>
                        </td>
                        <td v-text="item[1]"></td>
                        <td v-text="`${item[2]}%`"></td>
                    </tr>
                </tbody>
            </table>
            <h2 class="title">新老访客:</h2>
            <table class="table" v-if="CommonTrackRpt">
                <thead>
                    <tr>
                        <td class="visit-type-icon">
                            <img src="<?php echo plugins_url('views/visit-type-icon.png' , dirname(__FILE__)); ?>" />
                        </td>
                        <td class="visit-type-new">
                            <div class="visit-type-summary-title">新访客</div>
                            <div class="visit-type-summary-data">{{visitType.newVisitor.ratio}}<span class="ratil-unit">%</span></div>
                        </td>
                        <td class="visit-type-old">
                            <div class="visit-type-summary-title">老访客</div>
                            <div class="visit-type-summary-data">{{visitType.oldVisitor.ratio}}<span class="ratil-unit">%</span></div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="visit-type-detail-name">浏览量</td>
                        <td class="visit-type-detail-new" v-text="visitType.newVisitor.pv_count"></td>
                        <td class="visit-type-detail-old" v-text="visitType.oldVisitor.pv_count"></td>
                    </tr>
                    <tr>
                        <td class="visit-type-detail-name">访客数</td>
                        <td class="visit-type-detail-new" v-text="visitType.newVisitor.visitor_count"></td>
                        <td class="visit-type-detail-old" v-text="visitType.oldVisitor.visitor_count"></td>
                    </tr>
                    <tr>
                        <td class="visit-type-detail-name">跳出率</td>
                        <td class="visit-type-detail-new" v-text="`${visitType.newVisitor.bounce_ratio}%`"></td>
                        <td class="visit-type-detail-old" v-text="`${visitType.oldVisitor.bounce_ratio}%`"></td>
                    </tr>
                    <tr>
                        <td class="visit-type-detail-name">平均访问时长</td>
                        <td class="visit-type-detail-new">{{visitType.newVisitor.avg_visit_time | toMinutes}}</td>
                        <td class="visit-type-detail-old">{{visitType.oldVisitor.avg_visit_time | toMinutes}}</td>
                    </tr>
                    <tr>
                        <td class="visit-type-detail-name">平均访问页数</td>
                        <td class="visit-type-detail-new" v-text="visitType.newVisitor.avg_visit_pages"></td>
                        <td class="visit-type-detail-old" v-text="visitType.oldVisitor.avg_visit_pages"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    new Vue({
        name: '百度统计',
        el: '#tongji-app',
        data: {
            days: 1,
            TrendTimeA: '', // 趋势分析
            DistrictRpt: '', // 地域分布
            CommonTrackRpt: '' // 来源网站、入口页面、受访页面、新老访客、搜索词
        },
        watch: {
            days: function() {
                this.TimeTrendRpt = ''
                this.DistrictRpt = ''
                this.CommonTrackRpt = ''
                this.getData()
            }
        },
        computed: {
            trendTime() {
                return this.TrendTimeA.body.data[0].result
            },
            district() {
                return this.DistrictRpt.body.data[0].result
            },
            landingPage() {
                return this.CommonTrackRpt.body.data[0].result.landingPage
            },
            sourceSite() {
                return this.CommonTrackRpt.body.data[0].result.sourceSite
            },
            visitPage() {
                return this.CommonTrackRpt.body.data[0].result.visitPage
            },
            visitType() {
                return this.CommonTrackRpt.body.data[0].result.visitType
            },
            word() {
                return this.CommonTrackRpt.body.data[0].result.word
            }
        },
        filters: {
            toMinutes(second) {
                if (second != '--') {
                    return [
                    parseInt(second / 60 / 60),
                    parseInt((second / 60) % 60),
                    second % 60
                    ]
                    .join(':')
                    .replace(/\b(\d)\b/g, '0$1')
                } else {
                    return second
                }
            }
        },
        methods: {
            getBeforeDate(days) {
                let date = new Date()
                date.setTime(date.getTime() - days * 24 * 3600 * 1000)
                return (
                    date.getFullYear() +
                    (Array(2).join('0') + (date.getMonth() + 1)).slice(-2) +
                    (Array(2).join('0') + date.getDate()).slice(-2)
                )
            },
            getTrendTimeData(){
                jQuery.ajax({
                    type: 'GET',
                    url: ajaxurl,
                    data: {
                        action: 'get_tongji',
                        method: 'trend/time/a',
                        metrics: 'pv_count,visitor_count,ip_count,bounce_ratio,avg_visit_time,trans_count',
                        start_date: this.getBeforeDate(this.days)
                    },
                    success: (res)=>{
                        this.TrendTimeA = res
                    }       
                })
            },
            getCommonTrackData(){
                jQuery.ajax({
                    type: 'GET',
                    url: ajaxurl,
                    data: {
                        action: 'get_tongji',
                        method: 'overview/getCommonTrackRpt',
                        start_date: this.getBeforeDate(this.days)
                    },
                    success: (res)=>{
                        this.CommonTrackRpt = res
                    }       
                })
            },
            getDistrictData(){
                jQuery.ajax({
                    type: 'GET',
                    url: ajaxurl,
                    data: {
                        action: 'get_tongji',
                        method: 'overview/getDistrictRpt',
                        start_date: this.getBeforeDate(this.days)
                    },
                    success: (res)=>{
                        this.DistrictRpt = res
                    }       
                })
            }
        },
        mounted(){
            this.getTrendTimeData()
            this.getCommonTrackData()
            this.getDistrictData()
        }
    })
</script>