    <div class="wrap">
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
                <ul class="filter">
                    <li><button class="button" @click="days=1" :class="{'button-primary': days == 1}">今天</button></li>
                    <li><button class="button" @click="days=2" :class="{'button-primary': days == 2}">昨天</button></li>
                    <li><button class="button" @click="days=7" :class="{'button-primary': days == 7}">最近7天</button></li>
                    <li><button class="button" @click="days=30" :class="{'button-primary': days == 30}">最近30天</button></li>
                </ul>
                <div class="clear"></div>
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
                                <img src="<?php echo plugins_url('assets/images/visit-type-icon.png' , dirname(__FILE__)); ?>" />
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
</div>