import { Component, OnInit } from '@angular/core';
import { Router, Routes } from '@angular/router';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.css']
})
export class NavComponent implements OnInit {

  links = [
    {
      'url': '/home',
      'name': 'Home'
    },
    {
      'url': '/register',
      'name': 'Registration'
    },
    {
      'url': '/login',
      'name': 'Login'
    }
  ];

  constructor() { }

  ngOnInit() {
  }

}
