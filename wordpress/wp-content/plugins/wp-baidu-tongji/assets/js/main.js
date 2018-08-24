new Vue({
    name: '百度统计',
    el: '#wp-baidu-tongji-app',
    data: {
        message: '',
        showPassword: true,
        showToken: true,
        options: {},
        days: 1,
        TrendTimeA: '', // 趋势分析
        DistrictRpt: '', // 地域分布
        CommonTrackRpt: '' // 来源网站、入口页面、受访页面、新老访客、搜索词
    },
    watch: {
        days() {
            this.TimeTrendRpt = ''
            this.DistrictRpt = ''
            this.CommonTrackRpt = ''
            this.getTrendTimeData()
            this.getCommonTrackData()
            this.getDistrictData()
        }
    },
    computed: {
        currentPage() {
            return this.getQueryString('page')
        },
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
        getQueryString(name){
            const reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            const r = window.location.search.substr(1).match(reg);
            if (r!=null) return r[2]; return '';
        },
        closeNotice(){
            this.message = ''
        },
        getOptions(){
            jQuery.ajax({
                type    :'POST',
                url     : ajaxurl,
                data    : {
                    action: 'get_options'
                },
                success: (data)=> {
                    this.options = JSON.parse(data)
                }       
            })
        },
        setOptions(){
			jQuery.ajax({
				type    :'POST',
				url     : ajaxurl,
				data    : {
                    action: 'set_options',
                    ...this.options
				},
				success: (data)=> {
                    this.message = data
				}       
			})
        },
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
        if(this.currentPage == 'tongji-settings'){
            this.getOptions()
        }else{
            this.getTrendTimeData()
            this.getCommonTrackData()
            this.getDistrictData()
        }
    }
})
