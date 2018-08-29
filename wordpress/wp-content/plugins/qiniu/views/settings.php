<main id='qiniu-settings'>
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
    <div class="qiniu_settings">
        <div class="top_part">
            <div class="a_logo">
                <a target="_blank" href="http://qiniu.com/"><div class="logo"></div></a>
            </div>
            <h2>欢迎使用七牛</h2>
        </div>
        <div class="outer_opt wrap">
            <div class="acc_link">
                <p>七牛云是国内领先的以视觉智能和数据智能为核心的企业级云计算服务商，同时也是国内最有特色的智能视频云服务商，累计为 70 多万家企业提供服务，覆盖了国内80%网民。围绕富媒体场景推出了对象存储、融合 CDN 加速、容器云、大数据平台、深度学习平台等产品、并提供一站式智能视频云解决方案。为各行业及应用提供可持续发展的智能视频云生态，帮助企业快速上云，创造更广阔的商业价值。</p>
                <p>为了使用七牛云存储，你需要注册一个七牛账号，并通过认证后，每月就有10G的<strong>免费</strong>空间。<a target="_blank" href="https://portal.qiniu.com/signup?code=3le1u8u9qtyky"><strong>立即注册</strong></a>。</p>
                <p>注册完成后，登录到管理控制台，选择右上角的个人面板里的<a target="_blank" href="https://portal.qiniu.com/user/key">密钥管理</a>:复制AK和SK，并填到下面即可。</p>
            </div>
        </div>
        <div class="clear"></div>
        <div class="updated settings-success set_option" v-if="message">
            <p><strong>{{message}}</strong></p>
        </div>
        <div class="qiniu_config_in">
            <form action="" method="post" id="qiniu_options_form">
            <?php wp_nonce_field('qiniu_update_options'); ?>
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="accessKey">AK</label>
                        </th>
                        <td>
                            <input :type="showAK?'password':'text'" id="accessKey" v-model="options.qiniu_ak" class="regular-text">
                            <button @click="showAK=!showAK" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
                                <span class="dashicons" :class="showAK?'dashicons-visibility':'dashicons-hidden'"></span>  
                                <span class="text" v-text="showAK?'<?php _e('Show'); ?>':'<?php _e('Hide'); ?>'"></span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="secretKey">SK</label>
                        </th>
                        <td>
                            <input :type="showSK?'password':'text'" id="secretKey" v-model="options.qiniu_sk" class="regular-text">
                            <button @click="showSK=!showSK" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
                                <span class="dashicons" :class="showSK?'dashicons-visibility':'dashicons-hidden'"></span>  
                                <span class="text" v-text="showSK?'<?php _e('Show'); ?>':'<?php _e('Hide'); ?>'"></span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td>
                            <p name="submit" @click="setOptions" class="button button-primary"><?php echo __('Save'); ?></p>
                            <p class="button button-primary submit_progress" style="display:none;">更新中...</p>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</main>