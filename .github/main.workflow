workflow "Deploy" {
  resolves = ["WordPress Plugin Deploy"]
  on = "push"
}

# Filter for tag
action "tag" {
  uses = "actions/bin/filter@master"
  args = "tag"
}

action "WordPress Plugin Deploy" {
  needs = ["tag"]
  uses = "10up/actions-wordpress/dotorg-plugin-deploy@master"
  env = {
    SLUG = "flat-preloader"
  }
  secrets = ["GITHUB_TOKEN", "SVN_PASSWORD", "SVN_USERNAME"]
}
