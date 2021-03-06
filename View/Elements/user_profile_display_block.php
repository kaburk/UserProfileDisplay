<?php
/**
 * [PUBLISH] UserProfileDisplay
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 * 
 * このファイルは、ユーザープロフィールディスプレイを利用する際の利用例を記述したサンプルファイルです。
 * 記事詳細用や記事一覧表示用のビュー・ファイルに記述することで、
 * ユーザープロフィールディスプレイに入力した内容を反映できます。
 */
?>
<?php if (!empty($post['UserProfileDisplay'])): ?>
	<?php if ($this->UserProfileDisplay->allowPublish($post, 'UserProfileDisplay')): ?>
<div id="UserProfileDisplayBlock" class="cleafix">
	<div class="profile-box">
		<h3>この記事を書いたひと</h3>
		<div class="profile-img">
			<?php echo $this->UserProfileDisplay->getShowImage($post) ?>
		</div>
		<div class="profile-txt">
			<h4><?php echo h($this->UserProfileDisplay->getShowName($post)) ?></h4>
			
			<?php if ($post['UserProfileDisplay']['belong']): ?>
			<p><?php echo h($post['UserProfileDisplay']['belong']) ?></p>
			<?php endif ?>
			<?php if ($post['UserProfileDisplay']['title']): ?>
			<p><?php echo h($post['UserProfileDisplay']['title']) ?></p>
			<?php endif ?>
			<ul><?php if ($post['UserProfileDisplay']['website']): ?>
				<li>URL: <?php $this->BcBaser->link($post['UserProfileDisplay']['website'], $post['UserProfileDisplay']['website']) ?></li>
				<?php endif ?>
				<?php if ($post['UserProfileDisplay']['website2']): ?>
				<li>URL: <?php $this->BcBaser->link($post['UserProfileDisplay']['website2'], $post['UserProfileDisplay']['website2']) ?></li>
				<?php endif ?>
				<?php if ($post['UserProfileDisplay']['twitter']): ?>
				<li>Twitter: <?php $this->BcBaser->link($post['UserProfileDisplay']['twitter'], $post['UserProfileDisplay']['twitter']) ?></li>
				<?php endif ?>
				<?php if ($post['UserProfileDisplay']['facebook']): ?>
				<li>facebook: <?php $this->BcBaser->link($post['UserProfileDisplay']['facebook'], $post['UserProfileDisplay']['facebook']) ?></li>
				<?php endif ?>
				<?php if ($post['UserProfileDisplay']['google']): ?>
				<li>Google＋: <?php $this->BcBaser->link($post['UserProfileDisplay']['google'], $post['UserProfileDisplay']['google']) ?></li>
				<?php endif ?>
			</ul>
		</div>
		<?php if ($post['UserProfileDisplay']['description']): ?>
		<div class="profile-description">
			<?php echo $post['UserProfileDisplay']['description'] ?>
		</div>
		<?php endif ?>
		<?php if ($post['UserProfileDisplay']['show_blog_post']): ?>
			<?php $userBlogPosts = $this->UserProfileDisplay->getBlogPosts($post) ?>
			<div class="profile-blog-post">
				<h5>このひとの最新記事</h5>
				<ul>
			<?php if ($userBlogPosts): ?>
				<?php foreach ($userBlogPosts as $value): ?>
					<li><?php echo $this->Blog->getPostTitle($value) ?></li>
				<?php endforeach ?>
			<?php else: ?>
					<li>記事がありません。</li>
				</ul>
			<?php endif ?>
			</div>
		<?php endif ?>
	</div>
</div>
	<?php endif ?>
<?php endif ?>
