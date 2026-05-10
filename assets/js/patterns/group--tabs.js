/**
 * タブ機能 - Vanilla JavaScript実装
 * パフォーマンス最適化版
 */

(function() {
    'use strict';

    class TabManager {
        constructor(wrapper) {
            this.wrapper = wrapper;
            this.tabLinks = this.wrapper.querySelectorAll('.tab-link');
            this.contentWrapper = this.wrapper.querySelector('.tab-content-wrapper');
            this.contents = this.contentWrapper ? Array.from(this.contentWrapper.children) : [];

            if (this.tabLinks.length === 0 || this.contents.length === 0) {
                console.warn('Tab links or contents not found');
                return;
            }

            this.init();
        }

        init() {
            // 初期状態: 最初のタブをアクティブに
            this.setActiveTab(0);

            // クリックイベント登録
            this.tabLinks.forEach((link, index) => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.switchTab(index);
                });

                // キーボードナビゲーション（アクセシビリティ）
                link.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.switchTab(index);
                    }
                });
            });

            // 高さ調整
            this.adjustHeight();

            // リサイズ時に高さを再調整
            window.addEventListener('resize', () => this.adjustHeight());
        }

        switchTab(index) {
            // 範囲チェック
            if (index < 0 || index >= this.contents.length) return;

            // すべてのタブから active を削除
            this.tabLinks.forEach(link => link.classList.remove('active'));
            this.contents.forEach(content => content.classList.remove('active'));

            // 選択されたタブを active に
            this.tabLinks[index].classList.add('active');
            this.contents[index].classList.add('active');

            // 高さ調整
            this.adjustHeight(index);

            // カスタムイベント発火（拡張用）
            this.wrapper.dispatchEvent(new CustomEvent('tab-switched', {
                detail: { index: index, content: this.contents[index] }
            }));
        }

        setActiveTab(index) {
            this.switchTab(index);
        }

        adjustHeight(activeIndex = null) {
            // アクティブなコンテンツを特定
            if (activeIndex === null) {
                activeIndex = 0;
                for (let i = 0; i < this.contents.length; i++) {
                    if (this.contents[i].classList.contains('active')) {
                        activeIndex = i;
                        break;
                    }
                }
            }

            const activeContent = this.contents[activeIndex];
            if (!activeContent) return;

            // 一時的に表示して高さを取得
            const wasHidden = activeContent.style.position === 'absolute';
            if (wasHidden) {
                activeContent.style.position = 'relative';
                activeContent.style.visibility = 'hidden';
                activeContent.style.opacity = '1';
            }

            // コンテンツラッパーの高さを調整
            const height = activeContent.offsetHeight;
            this.contentWrapper.style.height = `${height}px`;

            // 元に戻す
            if (wasHidden) {
                activeContent.style.position = '';
                activeContent.style.visibility = '';
                activeContent.style.opacity = '';
            }
        }
    }

    // 初期化
    // `.tabs` は variation 登録前の旧マークアップ（Synced Pattern 等）互換用
    function initTabs() {
        const tabWrappers = document.querySelectorAll('.is-style-tabs, .tabs');

        tabWrappers.forEach(wrapper => {
            if (!wrapper._tabInitialized) {
                new TabManager(wrapper);
                wrapper._tabInitialized = true;
            }
        });
    }

    // DOM読み込み後に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTabs);
    } else {
        initTabs();
    }

    // エディタ内での動的挿入に対応
    if (window.wp && window.wp.data) {
        // Gutenbergエディタ内
        let timeout;
        window.wp.data.subscribe(() => {
            clearTimeout(timeout);
            timeout = setTimeout(initTabs, 100);
        });
    }

})();
