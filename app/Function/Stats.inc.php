<?php
// 管理员界面统计内容
function today_stats()
{
    $today = date('Ymd');
    $cache = \Mini\Cache\Cache::factory('File');

    if ($cache->get('stats_' . $today) == null) {
        $user = new \app\Model\User();
        $onepager = new \app\Model\Onepager();
        $topup = new \app\Model\Topup();
        $comment = new \app\Model\Comment();
        $stats = [
            'total_user' => $user->getTotalCount(),
            'total_credit' => $user->getCreditSum(),
            'active_user' => $user->getActiveUserCount(),
            'total_onepager' => $onepager->getTotalCount(false, false, 'All'),
            'paid_onepager' => $onepager->getPaidOnepagerCount(),
            'download_summary' => $onepager->getDownloadSummary(),
            'topup_summary' => $topup->getTopupSummary(),
            'total_comment' => $comment->getTotalCount(),
            'time_record' => date('H:i')
        ];
        $cache->set('stats_' . $today, $stats, 3600 * 24 * 2);
    } else {
        $stats = $cache->get('stats_' . $today);
    }

    return $stats;
}

function yesterday_stats()
{
    $yesterday = date('d') - 1;
    $yesterday = date('Ym') . $yesterday;
    $cache = \Mini\Cache\Cache::factory('File');

    if ($cache->get('stats_' . $yesterday) == null) {
        $stats = [
            'total_user' => 'Unknown',
            'total_credit' => 'Unknown',
            'active_user' => 'Unknown',
            'total_onepager' => 'Unknown',
            'paid_onepager' => 'Unknown',
            'download_summary' => 'Unknown',
            'topup_summary' => 'Unknown',
            'total_comment' => 'Unknown',
            'time_record' => 'Unknown'
        ];
    } else {
        $stats = $cache->get('stats_' . $yesterday);
    }

    return $stats;
}