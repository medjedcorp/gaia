<?php

namespace App\Services;


class SeirekiService
{
  // 和暦 => 西暦
  function convJtGDate($wareki)
  {
    $wareki = str_replace(" ", "", $wareki);
    $a = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $g = mb_substr($wareki, 0, 2, 'UTF-8');
    array_unshift($a, $g);
    if (($g !== '明治' && $g !== '大正' && $g !== '昭和' && $g !== '平成' && $g !== '令和') || (str_replace($a, '', $wareki) !== '年月日' && str_replace($a, '', $wareki) !== '元年月日')) {
      return false;
    } else {
      $y = strtok(str_replace($g, '', $wareki), '年月日');
      $m = strtok('年月日');
      $d = strtok('年月日');
      if (mb_strpos($wareki, '元年') !== false) {
        $y = 1;
      } else {
        if ($g === '令和') {
          $y += 2018;
        } elseif ($g === '平成') {
          $y += 1988;
        } elseif ($g === '昭和') {
          $y += 1925;
        } elseif ($g === '大正') {
          $y += 1911;
        } elseif ($g === '明治') {
          $y += 1868;
        }
        if (strlen($y) !== 4 || !checkdate($m, $d, $y)) {
          return false;
        }
      }
      return $y . '-' . $m . '-' . $d . ' 00:00:00';
    }
  }
}
