#!/usr/bin/env python
def preprocess(req):
    if req.get_full_url().endswith("index.php"):
        if not req.data:
            req.data = b'init=4a88b0c79048ea94fd29eee84772e0aa'
