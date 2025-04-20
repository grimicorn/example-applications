@extends('errors::layout')

@section('title', '422 Unprocessable Entity')

@section('message', $exception->getMessage())
