<?php
class MainMenu extends CWidget
{
    public function run()
    {
		if ( (!$menu = $value=Yii::app()->cache->get(Yii::app()->language.'_MainMenu_items')) || (!$links = $value=Yii::app()->cache->get(Yii::app()->language.'_MainMenu_links')) ) {
			$menu=array();
			$items = Menu::model()->with(array(
				'lang_data' => array(
					'select'=>'ml_title, ml_image',
					'joinType'=>'LEFT JOIN',
					'condition'=>'ml_lang = "'.Yii::app()->language.'"',
				),			
			))->findAll(array(
				'condition'	=>'m_active=1',
				'order'		=> 'm_id ASC',
			));
			$items = AdminController::compile_items($items, 'lang_data', array(
				'm_id'				=> 'm_id',
				'm_par_id'			=> 'm_par_id',
				'ml_title'			=> 'ml_title',
				'm_type'			=> 'm_type',
				'm_resource_val'	=> 'm_resource_val',
				'ml_image'			=> 'ml_image',
			));
			
			$in_list = array(
				1 => array(0),
				2 => array(0),
			);
			foreach ( $items as $item ) {
				$in_list[$item['m_type']][] = (int)$item['m_resource_val'];
				$item['m_par_id'] = (int)$item['m_par_id'];
				if ( !isset($menu[$item['m_par_id']]) )
					$menu[$item['m_par_id']] = array();
				$menu[$item['m_par_id']][] = $item;
			}
			
			$links = array(
				1 => array(),
				2 => array(),
				3 => array(1=>'news'),
				4 => array(1=>'guestbook'),
				5 => '/',
				6 => 'catalog',
				7 => 'locations',
			);
			
			
			$articles = ContentArticles::model()->findAll('ca_id IN ('.implode(',', $in_list[1]).')');
			foreach ( $articles as $article ) {
				$links[1][$article->ca_id] = $article->ca_alias;
			}
			$categs = ContentCategs::model()->findAll('cc_id IN ('.implode(',', $in_list[2]).')');
			foreach ( $categs as $categ ) {
				$links[2][$categ->cc_id] = $categ->cc_alias;
			}
			Yii::app()->cache->set(Yii::app()->language.'_MainMenu_items', $menu);
			Yii::app()->cache->set(Yii::app()->language.'_MainMenu_links', $links);
		}
        $this->render('MainMenu', array(
			'menu'			=> $menu,
			'links'			=> $links,
		));
    }
}
?>