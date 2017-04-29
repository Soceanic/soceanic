import { Component, OnInit, Input } from '@angular/core';
import { Post } from 'app/services/objects/post';

@Component({
  selector: 'app-feed-post',
  templateUrl: './feed-post.component.html',
  styleUrls: ['./feed-post.component.css']
})
export class FeedPostComponent implements OnInit {

  @Input() post: Post;

  constructor() { }

  ngOnInit() {
  }

}
