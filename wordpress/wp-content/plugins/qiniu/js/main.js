const mixin = {
    computed: {
        currentPage() {
            return this.getQueryString('page')
        }
    },
    methods: {
        getQueryString(name){
            const reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)")
            const r = window.location.search.substr(1).match(reg)
            if (r!=null) return r[2]; return ''
        }
    }
}

if(document.querySelector('#qiniu-settings')){
    new Vue({
        name: '七牛设置',
        el: '#qiniu-settings',
        mixins: [mixin],
        data: {
            message: '',
            showAK: true,
            showSK: true,
            options: {}
        },
        methods: {
            getOptions(){
                jQuery.ajax({
                    type    :'POST',
                    url     : ajaxurl,
                    data    : {
                        action: 'get_options'
                    },
                    success: (data)=> {
                        this.options = data
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
            }
        },
        mounted(){
            this.getOptions();
        }
    })
}

if(document.querySelector('#qiniu-library')){
    new Vue({
        name: '七牛媒体库',
        el: '#qiniu-library',
        mixins: [mixin],
        data: {
            mode: 'list',
            currentBucket: null,
            currentDomain: null,
            bucketFiles: [],
            search: '',
            searchFiles: []
        },
        filters: {
            HumanReadableFilesize(size) {
                const mod = 1024
                const units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB']
                let i = 0
                for (i = 0; size > mod; i++) {
                    size /= mod
                }
                return size.toFixed(2) + ' ' + units[i]
            },
            formatDate(date){
                return new Date(date/10000 + 8*3600000).toISOString().substr(0, 10)
            }
        },
        computed: {
            marker(){
                return this.bucketFiles.marker ? this.bucketFiles.marker : ''
            }
        },
        methods: {
            init(){
                this.currentBucket = jQuery('#bucket-filter').val()
                this.currentDomain = jQuery('#domain-filter').val()
            },
            initPlugin(){
                (function($){
                    setTimeout(function(){
                        $('.mfp-inline').magnificPopup({
                            type: 'inline'
                        })
                        $('.mfp-iframe').magnificPopup({
                            disableOn: 700,
                            type: 'iframe',
                            mainClass: 'mfp-fade',
                            removalDelay: 160,
                            preloader: false,
                            fixedContentPos: false
                        })
                    }, 1000)
                })(jQuery)
            },
            getBucketFiles(){
                jQuery.ajax({
                    type    :'POST',
                    url     : ajaxurl,
                    data    : {
                        action: 'get_bucket_files',
                        bucket: this.currentBucket,
                        'marker': this.marker,
                        prifix: this.search
                    },
                    success: (data)=> {
                        this.bucketFiles = data
                    }       
                })
            },
            copyURL(key, event){
                const value = this.currentDomain + '/' + key
                if (event.clipboardData) {
                    event.clipboardData.setData('text/plain', value)
                } else if (window.clipboardData) {
                    window.clipboardData.setData('text', value);
                }
            },
            del(item){
                jQuery.ajax({
                    type    :'POST',
                    url     : ajaxurl,
                    data    : {
                        action: 'del_bucket_file',
                        bucket: this.currentBucket,
                        'key': item.key
                    },
                    success: ()=> {
                        const index = this.bucketFiles.items.indexOf(item)
                        this.bucketFiles.items.splice(index, 1)
                    }       
                })
            }
        },
        mounted(){
            this.init()
            this.getBucketFiles()
            this.initPlugin()
        }
    })
}