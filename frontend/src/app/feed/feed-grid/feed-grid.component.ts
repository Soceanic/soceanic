import { Component, OnInit } from '@angular/core';
import { Post } from '../../services/objects/post';
import { FeedService } from '../../services/feed.service'

@Component({
  selector: 'app-feed-grid',
  templateUrl: './feed-grid.component.html',
  styleUrls: ['./feed-grid.component.css']
})

export class FeedGridComponent implements OnInit {

  feed: [Post];

  constructor(private service: FeedService) { }

  ngOnInit() {
    let user = JSON.parse(localStorage.getItem('currentUser'));
    console.log(user);


    this.service.getFeed('Thakugan')
                .subscribe(
                  feed => {
                    console.log(feed);
                    this.feed = feed;
                  },
                  err => {
                    console.log(err);
                    this.feed = err;
                  }
                );
  }

}
