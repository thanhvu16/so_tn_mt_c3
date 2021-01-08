<?php

return [
    'application_name' => 'Tên ứng dụng',
    'backup_name' => 'Tên sao lưu',
    'disk' => 'Ổ đĩa',
    'newest_backup_size' => 'Dung lượng sao lưu mới nhất',
    'number_of_backups' => 'Số lượng bản sao lưu',
    'total_storage_used' => 'Tổng bộ nhớ đã sử dụng',
    'newest_backup_date' => 'Ngày sao lưu mới nhất',
    'oldest_backup_date' => 'Ngày sao lưu cũ nhất',
    'exception_message' => 'Thông báo ngoại lệ: :message',
    'exception_trace' => 'Exception trace: :trace',
    'exception_message_title' => 'Thông báo ngoại lệ',
    'exception_trace_title' => 'Exception trace',

    'backup_failed_subject' => 'Sao lưu không thành công :application_name',
    'backup_failed_body' => 'Quan : Đã xảy ra lỗi khi sao lưu :application_name',

    'backup_successful_subject' => 'Sao lưu mới thành công :application_name',
    'backup_successful_subject_title' => 'Sao lưu mới thành công!',
    'backup_successful_body' => 'Tin mới, một bản sao lưu mới của :application_name đã được tạo thành công trên ổ đĩa có tên :disk_name.',

    'cleanup_failed_subject' => 'Dọn dẹp các bản sao lưu của :application_name failed.',
    'cleanup_failed_body' => 'Đã xảy ra lỗi khi dọn dẹp các bản sao lưu của :application_name',

    'cleanup_successful_subject' => 'Dọn dẹp các bản sao lưu :application_name thành công',
    'cleanup_successful_subject_title' => 'Dọn dẹp các bản sao lưu thành công!',
    'cleanup_successful_body' => 'Dọn dẹp các bản sao lưu :application_name trên ổ đĩa :disk_name thành công.',

    'healthy_backup_found_subject' => 'The backups for :application_name on disk :disk_name are healthy',
    'healthy_backup_found_subject_title' => 'The backups for :application_name are healthy',
    'healthy_backup_found_body' => 'The backups for :application_name are considered healthy. Good job!',

    'unhealthy_backup_found_subject' => 'Important: The backups for :application_name are unhealthy',
    'unhealthy_backup_found_subject_title' => 'Important: The backups for :application_name are unhealthy. :problem',
    'unhealthy_backup_found_body' => 'The backups for :application_name on disk :disk_name are unhealthy.',
    'unhealthy_backup_found_not_reachable' => 'The backup destination cannot be reached. :error',
    'unhealthy_backup_found_empty' => 'There are no backups of this application at all.',
    'unhealthy_backup_found_old' => 'The latest backup made on :date is considered too old.',
    'unhealthy_backup_found_unknown' => 'Sorry, an exact reason cannot be determined.',
    'unhealthy_backup_found_full' => 'The backups are using too much storage. Current usage is :disk_usage which is higher than the allowed limit of :disk_limit.',
];
