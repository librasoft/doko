<?php

namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper as BaseHtmlHelper;

class HtmlHelper extends BaseHtmlHelper
{

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'meta' => '<meta{{attrs}}/>',
            'metalink' => '<link href="{{url}}"{{attrs}}/>',
            'link' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'mailto' => '<a href="mailto:{{url}}"{{attrs}}>{{content}}</a>',
            'image' => '<img src="{{url}}"{{attrs}}/>',
            'tableheader' => '<th{{attrs}}>{{content}}</th>',
            'tableheaderrow' => '<tr{{attrs}}>{{content}}</tr>',
            'tablecell' => '<td{{attrs}}>{{content}}</td>',
            'tablerow' => '<tr{{attrs}}>{{content}}</tr>',
            'block' => '<div{{attrs}}>{{content}}</div>',
            'blockstart' => '<div{{attrs}}>',
            'blockend' => '</div>',
            'tag' => '<{{tag}}{{attrs}}>{{content}}</{{tag}}>',
            'tagstart' => '<{{tag}}{{attrs}}>',
            'tagend' => '</{{tag}}>',
            'tagselfclosing' => '<{{tag}}{{attrs}}/>',
            'para' => '<p{{attrs}}>{{content}}</p>',
            'parastart' => '<p{{attrs}}>',
            'css' => '<link rel="{{rel}}" href="{{url}}"{{attrs}}/>',
            'style' => '<style{{attrs}}>{{content}}</style>',
            'charset' => '<meta charset="{{charset}}"/>',
            'ul' => '<ul{{attrs}}>{{content}}</ul>',
            'ol' => '<ol{{attrs}}>{{content}}</ol>',
            'li' => '<li{{attrs}}>{{content}}</li>',
            'javascriptblock' => '<script{{attrs}}>{{content}}</script>',
            'javascriptstart' => '<script>',
            'javascriptlink' => '<script src="{{url}}"{{attrs}}></script>',
            'javascriptend' => '</script>'
        ]
    ];

    protected $_crumbs_urls = [];

    public function addCrumb($name, $link = null, array $options = [])
    {
        if ($name === null) {
            $name = $this->_View->get('title');
        }
        $link = $this->Url->build($link, true);
        $this->_crumbs_urls[] = $link;

        return parent::addCrumb($name, $link, $options);
    }

    public function inCrumb($url)
    {
        return in_array($this->Url->build($url, true), $this->_crumbs_urls);
    }

    public function getCrumbs($separator = '>', $startText = false)
    {
        $output = '';
        $crumbs = $this->_prepareCrumbs($startText);

        for ($i = 0, $n = count($crumbs); $i < $n; $i++) {
            $output .= '<span class="breadcrumbs-item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';

            if ($i > 0) {
                $output .= '<span class="breadcrumbs-sep">' . $separator . '</span> ';
            }

            $output .= $this->link('<span class="breadcrumbs-title" itemprop="title">' . $crumbs[$i][0] . '</span>', $crumbs[$i][1], [
                'itemprop' => 'url',
                'escape' => false,
                'class' => 'breadcrumbs-link',
            ] + $crumbs[$i][2]);
            $output .= '</span> ';
        }

        return $output;
    }

}
