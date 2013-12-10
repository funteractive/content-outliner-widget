/**
 * content-outline.js
 *
 * @version    0.1.0-beta
 * @author     Keisuke Imura <keisuke@keisuke-imura.com>
 * @license    The MIT License
 * @link       http://funteractive.jp/
 */
;(function($){

	$.fn.extend({
        contentOutline: function(options){
			$.contentOutline.init(options, $(this));
		}
	});
	$.contentOutline = {
        //デフォルト値
        defaultOptions: {
            contentId: 'editorArea',
            contentClass: 'editorArea'
        },

		//初期設定
		init: function(options, $node){
            //オプションの統合
            this.setOptions(options);

            //親要素の定義
            this.$node = $node;
            //コンテンツエリアのid
            this.contentId = this.options.contentId;
            //コンテンツエリアのclass
            this.contentClass = this.options.contentClass;

			//コンテンツエリアの定義
			//idがあれば優先
			this.$content = '';
            //contentIdが設定されている場合
			if(this.contentId !== this.defaultOptions.contentId){
				this.$content = $('#' + this.contentId);
			}
            //contentClassが設定されている場合
            else if(this.contentClass !== this.defaultOptions.contentClass) {
				this.$content = $('.' + this.contentClass);
			}
            //contentIdもcontentClassもデフォルトのままの場合
            else {
                var $id = $('#' + this.contentId);
                var $class = $('.' + this.contentClass);
				if($id.length){
					this.$content = $id;
				} else {
					this.$content = $class;
				}
			}

			//見出しの抽出
            var headlineHtml = this.makeHeadlinesHtml();

            //見出しリストの表示
            if(headlineHtml) {
                $('#contentOutlineWidget').html(headlineHtml);
            }

			//見出しをクリックした時の処理
		},

		//オプションのセット
		setOptions: function(options){
            var that = this;
			this.options = $.extend(true, {}, that.defaultOptions, options);
		},

        //見出しのデータ抽出処理
        extractHeadline: function() {
            var headlinesArr = [];

            var tag = 'h2';
            var $headlines = this.$content.find(tag);
            $headlines.each(function(index, value) {
                //見出し固有のIDをつける
                var id = 'cow_' + tag + '_' + index;
                $(this).attr('id', id);

                //見出しのタグ、テキスト、リンクを1つのオブジェクトに抽出して配列に入れる
                headlinesArr.push({
                    tag: '<' + tag + '/>',
                    text: $(this).text(),
                    hash: '#' + id
                });
            });

            return headlinesArr;
        },

        //見出しリストの出力処理
        makeHeadlinesHtml: function() {
            var html = '<ul>';
            var headlinesArr = this.extractHeadline();

            $.each(headlinesArr, function(index, value) {
               html += '<li><a href="' + value.hash + '">' + value.text + '</a></li>'+ "\n";
            });
            html += '</ul>';

            return html;
        }
	};

})(jQuery);
