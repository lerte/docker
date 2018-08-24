<?php 
    $this->qiniu_include_assets();
    $ajax_nonce = wp_create_nonce('qiniu');
    // var_dump($this->bucketMgr->buckets());
?>

<main id="qiniu-app">
    <v-app light>
        <v-toolbar>
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <v-toolbar-title><?php _e('七牛'); ?></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn flat @click.stop="dialog = true"><?php _e('Settings'); ?></v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-content>
            <v-container>
                <?php list($buckets, $err) = $this->bucketMgr->buckets(true); if($err): ?>
                <div class="qiniu_library"><?php print_r($buckets); ?></div>
                <?php else: ?>
                <div class="qiniu_settings">
                    <div class="top_part">
                        <div class="a_logo"><a target="_blank" href="http://qiniu.com/"><div class="logo"></div></a></div>
                        <h2>欢迎使用七牛</h2>
                    </div>
                    <div class="outer_opt wrap">
                        <div class="acc_link">
                            <p>七牛云是国内领先的以视觉智能和数据智能为核心的企业级云计算服务商，同时也是国内最有特色的智能视频云服务商，累计为 70 多万家企业提供服务，覆盖了国内80%网民。围绕富媒体场景推出了对象存储、融合 CDN 加速、容器云、大数据平台、深度学习平台等产品、并提供一站式智能视频云解决方案。为各行业及应用提供可持续发展的智能视频云生态，帮助企业快速上云，创造更广阔的商业价值。</p>
                            <p>为了使用七牛云存储，你需要注册一个七牛账号，并通过认证后，每月就有10G的<strong>免费</strong>空间。<a target="_blank" href="https://portal.qiniu.com/signup?code=3le1u8u9qtyky"><strong>立即注册</strong></a>.</p>
                            <p>注册完成后，登录到管理控制台，选择右上角的个人面板里的<a target="_blank" href="https://portal.qiniu.com/user/key">密钥管理</a>:复制AK和SK，并填到下面即可。</p>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="updated settings-success set_option" style="display:none;">
                        <p><strong> 恭喜你，你现在可以打开七牛云存储来管理你的图片了</strong></p>
                    </div>
                    <div class="qiniu_config_in">
                        <form action="" method="post" id="qiniu_options_form">
                            <?php wp_nonce_field('qiniu_update_options'); ?>
                            <v-text-field
                                value="<?php echo get_option('qiniu_ak', ''); ?>"
                                :append-icon="showAK ? 'visibility_off' : 'visibility'"
                                :type="showAK ? 'text' : 'password'"
                                label="AK"
                                @click:append="showAK = !showAK">
                            </v-text-field>
                            <v-text-field
                                value="<?php echo get_option('qiniu_sk', ''); ?>"
                                :append-icon="showSK ? 'visibility_off' : 'visibility'"
                                :type="showSK ? 'text' : 'password'"
                                label="SK"
                                @click:append="showSK = !showSK">
                            </v-text-field>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </v-container>
        </v-content>
    </v-app>
</main>