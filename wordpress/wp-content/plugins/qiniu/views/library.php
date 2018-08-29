<main id='qiniu-library'>
    <div class='wrap'>
        <h2 class='nav-tab-wrapper'>
            <a class='nav-tab' :class="{'nav-tab-active': 'qiniu-library' == currentPage}" href='admin.php?page=qiniu-library'>
                <?php _e('Library'); ?>
            </a>
            <a class='nav-tab' :class="{'nav-tab-active': 'qiniu-settings' == currentPage}" href='admin.php?page=qiniu-settings'>
                <?php _e('Settings'); ?>
            </a>
        </h2>
    </div>

    <div class="wrap">
        <a href="http://localhost/wp-admin/media-new.php" class="page-title-action">添加</a>
        <hr class="wp-header-end">
        <form id="posts-filter" method="get">
            <h2 class="screen-reader-text">过滤媒体项目列表</h2>
            <div class="wp-filter">
                <div class="filter-items">
                    <input name="mode" value="list" type="hidden">
                    <div class="view-switch">
                        <a href="#" @click.stop.prevent="mode='list'" :class="{current: mode=='list'}" class="view-list" id="view-switch-list">
                            <span class="screen-reader-text">列表视图</span>
                        </a>
                        <a href="#" @click.stop.prevent="mode='grid'" :class="{current: mode=='grid'}" class="view-grid" id="view-switch-grid">
                            <span class="screen-reader-text">网格视图</span>
                        </a>
                    </div>
                    <label for="bucket-filter" class="screen-reader-text">按Bucket筛选</label>
                    <select class="bucket-filters" name="bucket-filter" id="bucket-filter" @change='init'>
                        <?php 
                            list($buckets, $err) = $this->bucketMgr->buckets(true);
                            foreach($buckets as $bucket){
                                echo "<option value='$bucket'>$bucket</option>";
                            }
                        ?>
                    </select>
                    <select class="domain-filters" name="domain-filter" id="domain-filter" @change='init'>
                        <?php 
                            list($buckets, $err) = $this->bucketMgr->buckets(true);
                            foreach($buckets as $bucket){
                                list($domains, $err) = $this->bucketMgr->domains($bucket);
                                foreach($domains as $domain){
                                    echo "<option value='$domain'>$domain</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="search-form">
                    <label for="media-search-input" class="screen-reader-text">搜索媒体</label>
                    <input placeholder="搜索媒体项目…" class="search" name="s" v-model="search" type="search">
                </div>
            </div>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-top" class="screen-reader-text">选择批量操作</label>
                    <select name="action" id="bulk-action-selector-top">
                        <option value="-1">批量操作</option>
                        <option value="delete">永久删除</option>
                    </select>
                    <input id="doaction" class="button action" value="应用" type="submit">
                </div>
                <h2 class="screen-reader-text">媒体项目列表导航</h2>
                <div class="tablenav-pages">
                    <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                    <span class="paging-input">第<label for="current-page-selector" class="screen-reader-text">当前页</label><input class="current-page" id="current-page-selector" name="paged" value="1" size="1" aria-describedby="table-paging" type="text"><span class="tablenav-paging-text">页，共<span class="total-pages">7</span>页</span></span>
                    <a class="next-page" href="http://api.fantem.cn/wp-admin/upload.php?mode=list&amp;paged=2"><span class="screen-reader-text">下一页</span><span aria-hidden="true">›</span></a>
                    </span>
                </div>
                <br class="clear">
            </div>
            <h2 class="screen-reader-text">媒体项目列表</h2>
            <table class="wp-list-table widefat fixed striped media">
                <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-1">全选</label>
                            <input id="cb-select-all-1" type="checkbox">
                        </td>
                        <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>文件</span>
                        </th>
                        <th scope="col" id="author" class="manage-column column-author sortable desc">
                            <span>大小</span>
                        </th>
                        <th scope="col" id="mime-type" class="manage-column column-mime-type sortable desc">
                            <span>类型</span>
                        </th>
                        <th scope="col" id="date" class="manage-column column-date sortable desc">
                            <span>日期</span>
                        </th>
                        <th scope="col" id="operation" class="manage-column column-operation sortable desc">
                            <span>操作</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <tr v-for="(item, index) in bucketFiles.items" :key="index">
                        <th class="author-other status-inherit">
                            <input value="item['key']" type="checkbox">
                        </th>
                        <td>{{item['key']}}</td>
                        <td>{{item['fsize'] | HumanReadableFilesize}}</td>
                        <td>{{item['mimeType']}}</td>
                        <td>{{item['putTime'] | formatDate}}</td>
                        <td>
                            <button :href="'#popup-inline-'+index" class="button button-primary mfp-inline" v-if="/^image\/?(png|jpeg|gif)$/.test(item['mimeType'])">
                                查看
                            </button>
                            <button :href="'http://'+currentDomain+'/'+item['key']" v-else class="button button-primary mfp-iframe">
                                查看
                            </button>
                            <div :id="'popup-inline-'+index" class="white-popup mfp-hide">
                                <img class="img-responsive" :src="'http://'+currentDomain+'/'+item['key']" />
                            </div>
                            <button class="button button-primary" @click.stop.prevent="copyURL(item['key'], $event)">复制</button>
                            <button class="button button-secondary" @click.stop.prevent="del(item)">删除</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-2">全选</label>
                            <input id="cb-select-all-2" type="checkbox">
                        </td>
                        <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>文件</span>
                        </th>
                        <th scope="col" class="manage-column column-author sortable desc">
                            <span>大小</span>
                        </th>
                        <th scope="col" class="manage-column column-mime-type sortable desc">
                            <span>类型</span>
                        </th>
                        <th scope="col" class="manage-column column-date sortable desc">
                            <span>日期</span>
                        </th>
                        <th scope="col" class="manage-column column-operation sortable desc">
                            <span>操作</span>
                        </th>
                </tfoot>
            </table>
            <div class="tablenav bottom">
                <div class="tablenav-pages no-pages">
                    <span class="displaying-num">0个项目</span>
                    <span class="pagination-links">
                        <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                        <span class="screen-reader-text">当前页</span>
                        <span id="table-paging" class="paging-input">
                            <span class="tablenav-paging-text">第1页，共<span class="total-pages">0
                            </span>页
                        </span>
                    </span>
                    <a class="next-page" href="http://localhost/wp-admin/upload.php?paged=0"><span class="screen-reader-text">下一页</span><span aria-hidden="true">›</span></a>
                    <a class="last-page" href="http://localhost/wp-admin/upload.php?paged=0"><span class="screen-reader-text">尾页</span><span aria-hidden="true">»</span></a></span>
                </div>
                <br class="clear">
            </div>
        </form>
    </div>
</main>