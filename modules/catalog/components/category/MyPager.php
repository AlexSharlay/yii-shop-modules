<?php

namespace common\modules\catalog\components\category;

use yii\helpers\Html;
use yii\widgets\LinkPager;

class MyPager extends LinkPager
{
    /**
     * @var string the name of the input checkbox input fields. This will be appended with `[]` to ensure it is an array.
     */
    public $separator = '...';
    /**
     * @var boolean turns on|off the <a> tag for the active page. Defaults to true (will be a link).
     */
    public $activePageAsLink = true;

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }
        $buttons = [];
        $currentPage = $this->pagination->getPage();
        // first page
        if ($this->firstPageLabel !== false) {
            $url = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
            $buttons[] = $this->returnUrl($url);
        }
        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            //влево
            $url = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
            $buttons[] = $this->returnUrl($url);
        }
        // page calculations
        list($beginPage, $endPage) = $this->getPageRange();
        $startSeparator = false;
        $endSeparator = false;
        $beginPage++;
        $endPage--;
        if ($beginPage != 1) {
            $startSeparator = true;
            $beginPage++;
        }
        if ($endPage + 1 != $pageCount - 1) {
            $endSeparator = true;
            $endPage--;
        }
        // smallest page
        $url = $this->renderPageButton(1, 0, null, false, 0 == $currentPage);
        $buttons[] = $this->returnUrl($url);
        // separator after smallest page
        if ($startSeparator) {
            $url = $this->renderPageButton($this->separator, null, null, true, false);
            $buttons[] = $this->returnUrl($url);
        }
        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            if ($i != 0 && $i != $pageCount - 1) {
                //текущие страницы
                $url = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
                $buttons[] = $this->returnUrl($url);
            }
        }
        // separator before largest page
        if ($endSeparator) {
            //многоточие
            $buttons[] = $this->renderPageButton($this->separator, null, null, true, false);
        }
        // largest page
        $url = $this->renderPageButton($pageCount, $pageCount - 1, null, false, $pageCount - 1 == $currentPage);
        $buttons[] = $this->returnUrl($url);
        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $url = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
            $buttons[] = $this->returnUrl($url);
        }
        // last page
        if ($this->lastPageLabel !== false) {
            $url = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
            $buttons[] = $this->returnUrl($url);
        }
        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        // active page as anchor or span
        if ($active && !$this->activePageAsLink) {
            return Html::tag('li', Html::tag('span', $label, $linkOptions), $options);
        }
        return Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }

    public function returnUrl($str)
    {
        $s = '?category=';
        $pos = stripos($str, $s);
        $urlTemp1 = substr($str, 0, $pos);
        $strLen = strlen($str);
        if (!empty($pos)) {
            $pos1 = stripos($str, '&amp;', $pos);
            if (!empty($pos1)) {
                $urlTemp2 = '?' . substr($str, $pos1 + 5, $strLen);
            } else {
                $pos2 = stripos($str, 'data-page', $pos);
                $urlTemp2 = substr($str, $pos2 - 2, $strLen);
            }
            $url = $urlTemp1 . $urlTemp2;
        } else {
            $url = $str;
        }

        return $url;
    }
}