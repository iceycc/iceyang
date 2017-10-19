<?php

// 载入全部公共函数
require_once '../functions.php';
// 判断是否登录
icey_get_current_user();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th width="100">作者</th>
            <th>评论</th>
            <th width="100">评论在</th>
            <th width="100">提交于</th>
            <th width="100">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody id="list"></tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'comments'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <!-- 建议模板类型格式 text/x-<template-engine-name> -->
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
    <tr>
      <td class="text-center"><input type="checkbox"></td>
      <td>{{: author }}</td>
      <td>{{: content }}</td>
      <td>《{{: post_id }}》</td>
      <td>{{: created }}</td>
      <td>{{: status === 'approved' ? '批准' : status === 'held' ? '待审' : '驳回' }}</td>
      <td class="text-center">
        <a href="post-add.html" class="btn btn-warning btn-xs">驳回</a>
        <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
      </td>
    </tr>
    {{/for}}
  </script>
  <!-- 页面中的JS最终需要摘取到单独的JS文件中 -->
  <script>
    $(function ($) {
      // 发送AJAX请求获取界面数据
      $.ajax({
        url: "/admin/api/comments.php",
        type: "get",
        // 设置响应的Content-Type为application/json  这里可以不设置
        dataType: "json",
        data: { page: 1 },
        success: function (res) {
          /**
           * 渲染数据
           */
          // 将数据渲染到表格中
          // 模板引擎使用：
          // 1. 引入模板引擎
          // 2. 准备一个模板
          // 3. 准备一个数据
          var context = { comments: res }
          console.log(context)
          var html = $("#comments_tmpl").render(context)
          console.dir(html)
          $("#list").html(html)       

        }
      })
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
