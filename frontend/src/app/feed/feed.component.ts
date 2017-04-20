import { Component, OnInit } from '@angular/core';
import { Post } from '../api/feed/post';
import { FeedService } from '../api/feed/feed.service';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

@Component({
  selector: 'app-feed',
  templateUrl: './feed.component.html',
  styleUrls: ['./feed.component.css']
})
export class FeedComponent implements OnInit {

  feed: [Post];
  currentUser: string = 'borisirl';
  

  constructor(private service: FeedService) { }

  ngOnInit() {
    this.feed = this.service.get();
  }

  addPost

}
