import { Component, OnInit } from '@angular/core';
import { Post } from '../../services/objects/post';

@Component({
  selector: 'app-feed-grid',
  templateUrl: './feed-grid.component.html',
  styleUrls: ['./feed-grid.component.css']
})
export class FeedGridComponent implements OnInit {

  feed: [Post];

  constructor() { }

  ngOnInit() {
  }

}
